PHPUNIT=phpunit.bat
PHPCI=pci.bat
SVN=svn

.PHONY: tests
tests:
	cd tests/; $(PHPUNIT) --colors .; cd ..

.PHONY: coverage
coverage:
	cd tests/; $(PHPUNIT) --coverage-html coverage/ .; cd ..

.PHONY: pci
pci:
	$(PHPCI) -id pcidirs.txt -d .

.PHONY: export
export:
	$(SVN) export -q . polyglott/
	rm -rf polyglott/polyglott.komodoproject polyglott/Makefile polyglott/pcidirs.txt polyglott/tests/
	cp polyglott/config/config.php polyglott/config/defaultconfig.php
	cp polyglott/languages/en.php polyglott/languages/default.php
