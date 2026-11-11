add:
	# Update Makefile
	git add Makefile
	git commit -m "build: update Makefile"

    # Update Statuses controller
	git add app/Http/Controllers/StatusesController.php
	git commit -m "fix(statuses): update statuses controller"

	# Update Statuses model
	git add app/Models/Statuses.php
	git commit -m "feat(statuses): update status model"

	# Update Sites view
	git add resources/views/sites/sites.blade.php
	git commit -m "feat(sites): update sites view"

	# Update Create Statuses view
	git add resources/views/statuses/create-statuses.blade.php
	git commit -m "feat(statuses): update create status view"

	# Update Edit Statuses view
	git add resources/views/statuses/edit-statuses.blade.php
	git commit -m "feat(statuses): update edit status view"

	# Update Show Statuses view
	git add resources/views/statuses/show-statuses.blade.php
	git commit -m "feat(statuses): update show status view"

	# Update Statuses listing view
	git add resources/views/statuses/statuses.blade.php
	git commit -m "feat(statuses): update statuses listing view"

	# Add migration for status colors
	git add database/migrations/2026_11_11_145210_add_set_color_to_status_table.php
	git commit -m "feat(statuses): add status color migration"
