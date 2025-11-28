#!/bin/bash

# Script to debug 500 error in production

echo "🔍 Debugging 500 Error..."
echo ""

# Check Laravel logs
echo "📋 Laravel Logs (last 50 lines):"
docker exec adojobs_app tail -50 /app/storage/logs/laravel.log 2>/dev/null || echo "No log file found or cannot read"
echo ""

# Check recent errors
echo "🚨 Recent Errors:"
docker exec adojobs_app tail -100 /app/storage/logs/laravel.log 2>/dev/null | grep -i "error\|exception\|fatal" | tail -20 || echo "No errors found"
echo ""

# Check environment
echo "🌍 Environment Check:"
docker exec adojobs_app php artisan tinker --execute="echo 'APP_ENV: ' . env('APP_ENV') . PHP_EOL; echo 'APP_DEBUG: ' . (env('APP_DEBUG') ? 'true' : 'false') . PHP_EOL; echo 'APP_KEY: ' . (env('APP_KEY') ? 'set' : 'NOT SET') . PHP_EOL;" 2>&1
echo ""

# Check database connection
echo "🔌 Database Connection:"
docker exec adojobs_app php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database: OK'; } catch (Exception \$e) { echo 'Database Error: ' . \$e->getMessage(); }" 2>&1
echo ""

# Check config cache
echo "⚙️  Config Cache:"
docker exec adojobs_app php artisan config:show app.name 2>&1 | head -5
echo ""

# Check storage permissions
echo "📁 Storage Permissions:"
docker exec adojobs_app ls -la /app/storage/logs/ 2>&1 | head -5
echo ""

# Test direct PHP execution
echo "🧪 Testing PHP Execution:"
docker exec adojobs_app php -r "echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;"
echo ""

# Check if APP_KEY is set
echo "🔑 APP_KEY Check:"
docker exec adojobs_app php artisan tinker --execute="echo config('app.key') ? 'APP_KEY is set' : 'APP_KEY is NOT set';" 2>&1
echo ""

# Check route cache
echo "🛣️  Route Cache:"
docker exec adojobs_app php artisan route:list 2>&1 | head -10 || echo "Route list failed"
echo ""

echo "✅ Debug complete!"

