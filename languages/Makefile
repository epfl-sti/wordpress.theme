# Makefile to automate tasks pertaining to translations.

.PHONY: all

all: compile epfl-sti-theme.pot

.PHONY: compile
compile: $(patsubst %.po, %.mo, $(wildcard *.mo))

%.mo: %.po
	msgfmt -o $@ $<

.PHONY: epfl-sti-theme.pot
epfl-sti-theme.pot:
	find .. -name "*.php" \
	| xargs grep -l i18n.php \
	| xargs \
	  xgettext -d "epfl-sti" -k"__x" -k"___" -k"__e" \
	  --package-name=epfl-sti-theme \
	  --msgid-bugs-address=stiitweb@groupes.epfl.ch \
	  --omit-header \
	  -j -o epfl-sti-theme.pot

.PHONY: msgmerge
msgmerge:
	@test -n "$(TARGET)" || { echo >&2 "Usage: make msgmerge TARGET=en_JP.po"; exit 1; }
	msgmerge -U $(TARGET) epfl-sti-theme.pot
