# Makefile to automate tasks pertaining to translations.

ALL_TRANSLATIONS=fr_FR.po

.PHONY: all
all:
	$(MAKE) epfl-sti-theme.pot
	for t in $(ALL_TRANSLATIONS); do $(MAKE) msgmerge TARGET=$$t; done
	$(MAKE) compile

.PHONY: compile
compile: $(patsubst %.po, %.mo, $(ALL_TRANSLATIONS))

%.mo: %.po
	msgfmt -o $@ $<

.PHONY: epfl-sti-theme.pot
epfl-sti-theme.pot:
	find .. -name "*.php" -or -name "*.inc" \
	  | xargs grep -l i18n.php \
	  | xargs \
	    xgettext -d "epfl-sti" -k"__x" -k"___" -k"__e" \
	    --from-code=utf-8 -L php \
	    --package-name=epfl-sti-theme \
	    --msgid-bugs-address=stiitweb@groupes.epfl.ch \
	    --omit-header \
	    -c -j -o $@

.PHONY: msgmerge
msgmerge:
	@test -n "$(TARGET)" || { echo >&2 "Usage: make msgmerge TARGET=en_JP.po"; exit 1; }
	msgmerge -U $(TARGET) epfl-sti-theme.pot
