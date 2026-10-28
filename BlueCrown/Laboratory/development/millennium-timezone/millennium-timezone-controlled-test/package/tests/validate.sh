#!/usr/bin/env sh
set -eu
ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
find "$ROOT" -name '*.php' -print | while IFS= read -r file; do php -l "$file"; done
php "$ROOT/tests/smoke.php"
