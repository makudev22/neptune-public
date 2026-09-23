#!/usr/bin/env sh
set -eu

caller_dir=$(pwd -P)
root=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
cd "$root"

php=${NEPTUNE_PHP:-}
case "$php" in
	""|/*) ;;
	*) php="$caller_dir/$php" ;;
esac
if [ -z "$php" ]; then
	for candidate in "$root/bin/php/php" "$root/bin/php7/bin/php" "$root/bin/php/bin/php"; do
		if [ -x "$candidate" ]; then
			php=$candidate
			break
		fi
	done
fi

if [ -z "$php" ] || [ ! -x "$php" ]; then
	printf '%s\n' 'Set NEPTUNE_PHP to the PocketMine-MP PHP executable or place it in bin/php/php.' >&2
	exit 1
fi

phar="$root/build/Neptune.phar"
if [ ! -f "$phar" ]; then
	printf '%s\n' 'build/Neptune.phar is missing. Build Neptune first.' >&2
	exit 1
fi

data=${NEPTUNE_DATA:-"$root/server-data"}
case "$data" in
	/*) ;;
	*) data="$caller_dir/$data" ;;
esac
exec "$php" "$phar" "--data=$data" "--plugins=$data/plugins" --settings.enable-dev-builds=true "$@"
