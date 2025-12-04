#!/bin/bash

# Pre-Flight Check Script for FAKTAnow Deployment
# Run this before deploying to catch any issues

echo "🔍 FAKTAnow Pre-Flight Check"
echo "============================"
echo ""

ERRORS=0
WARNINGS=0

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check 1: PHP Syntax
echo -n "Checking PHP syntax... "
if find app -name "*.php" -exec php -l {} \; 2>&1 | grep -q "Parse error"; then
    echo -e "${RED}❌ FAIL${NC}"
    ERRORS=$((ERRORS + 1))
else
    echo -e "${GREEN}✅ PASS${NC}"
fi

# Check 2: Composer dependencies
echo -n "Checking composer.json... "
if [ -f "composer.json" ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 3: Package.json
echo -n "Checking package.json... "
if [ -f "package.json" ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 4: .env.example
echo -n "Checking .env.example... "
if [ -f ".env.example" ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${YELLOW}⚠️  WARNING${NC}"
    WARNINGS=$((WARNINGS + 1))
fi

# Check 5: .gitignore has .env
echo -n "Checking .gitignore for .env... "
if grep -q "^\.env$" .gitignore; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - .env should be in .gitignore${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 6: Deployment files
echo -n "Checking deployment files... "
DEPLOY_FILES=("Procfile" "zeabur.json" ".env.zeabur" "nginx.conf")
MISSING_FILES=()

for file in "${DEPLOY_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        MISSING_FILES+=("$file")
    fi
done

if [ ${#MISSING_FILES[@]} -eq 0 ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - Missing: ${MISSING_FILES[*]}${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 7: Routes file
echo -n "Checking routes/web.php... "
if [ -f "routes/web.php" ]; then
    if php -l routes/web.php > /dev/null 2>&1; then
        echo -e "${GREEN}✅ PASS${NC}"
    else
        echo -e "${RED}❌ FAIL - Syntax error${NC}"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo -e "${RED}❌ FAIL - File not found${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 8: Controllers
echo -n "Checking controllers... "
CONTROLLER_ERRORS=0
for file in app/Http/Controllers/*.php; do
    if ! php -l "$file" > /dev/null 2>&1; then
        CONTROLLER_ERRORS=$((CONTROLLER_ERRORS + 1))
    fi
done

if [ $CONTROLLER_ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - $CONTROLLER_ERRORS files with errors${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 9: Models
echo -n "Checking models... "
MODEL_ERRORS=0
for file in app/Models/*.php; do
    if ! php -l "$file" > /dev/null 2>&1; then
        MODEL_ERRORS=$((MODEL_ERRORS + 1))
    fi
done

if [ $MODEL_ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - $MODEL_ERRORS files with errors${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 10: Migrations
echo -n "Checking migrations... "
if [ -d "database/migrations" ] && [ "$(ls -A database/migrations)" ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - No migrations found${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Check 11: Seeders
echo -n "Checking seeders... "
if [ -f "database/seeders/CategorySeeder.php" ] && [ -f "database/seeders/UserSeeder.php" ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${YELLOW}⚠️  WARNING - Some seeders missing${NC}"
    WARNINGS=$((WARNINGS + 1))
fi

# Check 12: Views
echo -n "Checking critical views... "
VIEWS=("resources/views/homepage.blade.php" "resources/views/admin/dashboard.blade.php" "resources/views/editor/dashboard.blade.php")
MISSING_VIEWS=()

for view in "${VIEWS[@]}"; do
    if [ ! -f "$view" ]; then
        MISSING_VIEWS+=("$view")
    fi
done

if [ ${#MISSING_VIEWS[@]} -eq 0 ]; then
    echo -e "${GREEN}✅ PASS${NC}"
else
    echo -e "${RED}❌ FAIL - Missing: ${MISSING_VIEWS[*]}${NC}"
    ERRORS=$((ERRORS + 1))
fi

# Summary
echo ""
echo "============================"
echo "📊 Summary"
echo "============================"
echo -e "✅ Passed: $((12 - ERRORS - WARNINGS))"
echo -e "${YELLOW}⚠️  Warnings: $WARNINGS${NC}"
echo -e "${RED}❌ Errors: $ERRORS${NC}"
echo ""

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Pre-flight check PASSED!${NC}"
    echo "Your application is ready for deployment."
    exit 0
else
    echo -e "${RED}❌ Pre-flight check FAILED!${NC}"
    echo "Please fix the errors before deploying."
    exit 1
fi
