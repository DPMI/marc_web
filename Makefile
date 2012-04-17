version=$(shell grep \$$version config.php | sed 's/^.*"\(.*\)".*$$/\1/')
dist:
	tar cvzf marc_web-$(version).tar.gz . \
		--exclude .git --exclude .gitignore \
		--exclude *.tar.gz \
		--exclude config.local.php \
		--exclude Makefile \
		--show-transformed-names --transform 's|./|marc_web-$(version)/|'
