

# Product Manager

A bare-bones PHP CRUD application built as a learning exercise. The goal was to understand PHP's core web mechanics — request handling, form processing, database interaction via PDO, and server-side HTML rendering — without any framework abstracting those concepts away.

The app manages a single entity (products) backed by SQLite. Each PHP file maps directly to one operation: list, create, edit, or delete. There's no routing layer, no templating engine, no JavaScript build step. Just the raw request/response cycle that every PHP framework is built on top of.



cd product-manager
php -S localhost:8000
