#!/bin/bash

./bin/console doctrine:database:create ||
./bin/console doctrine:schema:update --force &&
./bin/console doctrine:fixtures:load -n &&
./bin/console app:import:videos &&
./bin/console app:import:news &&
./bin/console app:import:iss &&
./bin/console app:import:asteroids &&
./bin/console app:import:mars-photos &&
./bin/console app:import:planet-position &&
./bin/console app:import:apod &&
./bin/console app:import:notifications
