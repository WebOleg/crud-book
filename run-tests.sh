#!/bin/bash

echo "🚀 Starting test environment..."

# Start test database
docker-compose -f docker-compose.test.yml up -d
sleep 3

echo "📊 Running database migrations..."
php artisan migrate:fresh --env=testing --force

echo "🧪 Running all tests..."
php artisan test --env=testing

echo "✅ Tests completed!"
