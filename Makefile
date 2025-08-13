.PHONY: test test-setup test-unit test-feature test-authors test-books test-models test-api

test-setup:
	docker-compose -f docker-compose.test.yml up -d
	sleep 3
	php artisan migrate:fresh --env=testing --force

test: test-setup
	php artisan test --env=testing

test-unit: test-setup
	php artisan test tests/Unit/ --env=testing

test-feature: test-setup
	php artisan test tests/Feature/ --env=testing

test-authors: test-setup
	php artisan test tests/Feature/Author/ --env=testing

test-books: test-setup
	php artisan test tests/Feature/Book/ --env=testing

test-models: test-setup
	php artisan test tests/Unit/ --env=testing

test-api: test-setup
	php artisan test tests/Feature/Api/ --env=testing

clean:
	docker-compose -f docker-compose.test.yml down
