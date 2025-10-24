# ---- Config ----
SAIL  := ./vendor/bin/sail
ART   := $(SAIL) artisan

# ---- Docker/Sail ----
.PHONY: up down build
up:
	$(SAIL) up -d
down:
	$(SAIL) down

# ---- Artisan wrappers ----
.PHONY: artisan migrate seed fresh mfs rollback reset
artisan:
	$(ART) $(CMD)

migrate:
	$(ART) migrate

seed:
	$(ART) db:seed

fresh:
	$(ART) migrate:fresh

mfs:
	$(ART) migrate:fresh --seed

rollback:
	$(ART) migrate:rollback

reset:
	$(ART) migrate:reset
