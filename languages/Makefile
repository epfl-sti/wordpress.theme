# Makefile to automate tasks pertaining to translations.

.PHONY: default

default: epfl-sti-theme.pot

.PHONY: epfl-sti-theme.pot
epfl-sti-theme.pot:
	find .. -name "*.php" \
	| xargs grep -l i18n.php \
	| xargs \
	  xgettext -d "epfl-sti" -k"__x" -k"___" -k"__e" \
	  --package-name=epfl-sti-theme \
	  --msgid-bugs-address=stiitweb@groupes.epfl.ch \
	  -j -o epfl-sti-theme.pot
