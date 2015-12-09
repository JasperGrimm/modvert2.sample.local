#!/usr/bin/env sh
SRC_DIR="`pwd`"
cd "`dirname "$0"`"
cd "../vendor/vestnik/modvert2/bin"
BIN_TARGET="`pwd`/modvert.cli.php"
cd "$SRC_DIR"
"$BIN_TARGET" "$@"
