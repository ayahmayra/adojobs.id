#!/bin/bash

# Script to check if database migration is needed after rebuild

set -e

echo "🔍 Checking Database Status After Rebuild..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/4] Checking database connection..."
DB_CHECK=$(docker exec adojobs_app php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch (Exception \$e) { echo 'FAIL'; }" 2>&1 | tail -1)
if [[ "$DB_CHECK" == *"OK"* ]]; then
    echo "✅ Database connection OK"
else
    echo "❌ Database connection failed"
    echo "   This might indicate database volume was removed"
    exit 1
fi
echo ""

echo "[2/4] Checking if migrations table exists..."
MIGRATIONS_TABLE=$(docker exec adojobs_app php artisan tinker --execute="try { DB::table('migrations')->count(); echo 'EXISTS'; } catch (Exception \$e) { echo 'NOT_EXISTS'; }" 2>&1 | tail -1)
if [[ "$MIGRATIONS_TABLE" == *"EXISTS"* ]]; then
    echo "✅ Migrations table exists - database has been migrated"
else
    echo "❌ Migrations table does NOT exist - need to run migrations"
    echo "   Run: docker exec adojobs_app php artisan migrate --force"
    exit 1
fi
echo ""

echo "[3/4] Checking migration status..."
MIGRATION_STATUS=$(docker exec adojobs_app php artisan migrate:status 2>&1 | tail -5)
echo "$MIGRATION_STATUS"
echo ""

echo "[4/4] Checking if tables exist..."
TABLES_COUNT=$(docker exec adojobs_app php artisan tinker --execute="echo DB::select('SHOW TABLES');" 2>&1 | grep -c "Table" || echo "0")
if [ "$TABLES_COUNT" -gt 0 ]; then
    echo "✅ Database has $TABLES_COUNT tables"
else
    echo "⚠️  Database appears to be empty"
    echo "   Run migrations: docker exec adojobs_app php artisan migrate --force"
fi
echo ""

echo "✅ Database check complete!"
echo ""
echo "📝 Summary:"
echo "   - If migrations table exists and has entries: ✅ NO migration needed"
echo "   - If migrations table doesn't exist: ❌ NEED to run migrations"
echo "   - If database connection fails: ❌ Check database container"

