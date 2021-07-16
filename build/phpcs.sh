#!/bin/bash

run_phpcs() {
	if php node_modules/PHP_CodeSniffer/bin/phpcs --config-set installed_paths node_modules/WordPress-Coding-Standards/ ; then
		if npm run phpcs-installStandards && node_modules/PHP_CodeSniffer/bin/phpcs -p -s --report-emacs --report-summary --report-width=220 --standard=WordPress-Docs --standard=WordPress-Extra --ignore=*/node_modules/*,*/vendor/* --extensions=php src/includes/configs/customizer/controls/; then
			echo "No PHPCS Errors Found"
			exit 0
		else
			while true; do
				read -p "Do you wish to run PHPCBF to automatically fix errors?" yn
				case $yn in
					[Yy]* ) run_phpcbf; break;;
					[Nn]* ) run_build_prompt; break;;
					* ) echo "Please answer yes or no.";;
				esac
			done
		fi
	else
		echo "PHPCS Installation failed"
		exit 1
	fi
}

run_phpcbf() {
	npm run phpcs-installStandards && node_modules/PHP_CodeSniffer/bin/phpcbf -p -s --report-emacs --report-summary --report-width=220 --standard=WordPress-Docs --standard=WordPress-Extra --ignore=*/node_modules/*,*/vendor/* --extensions=php src/includes/configs/customizer/controls/
	echo "Beautification done. Re-running PHPCS";
	re_run_phpcs
}

re_run_phpcs() {
	if php node_modules/PHP_CodeSniffer/bin/phpcs --config-set installed_paths node_modules/WordPress-Coding-Standards/ ; then
		if npm run phpcs-installStandards && node_modules/PHP_CodeSniffer/bin/phpcs -p -s --report-emacs --report-summary --report-width=220 --standard=WordPress-Docs --standard=WordPress-Extra --ignore=*/node_modules/*,*/vendor/* --extensions=php src/includes/configs/customizer/controls/; then
			echo "All Issues corrected by PHPCBF"
			exit 0
		else
			echo "There are still issues that cannot be corrected automatically"
			run_build_prompt
		fi
	else
		echo "PHPCS Installation failed"
		run_build_prompt
	fi
}

run_build_prompt() {
	while true; do
		read -p "Do you wish to continue build, without completing php-codesniffer linting?" yn
		case $yn in
			[Yy]* ) exit 0;;
			[Nn]* ) exit 1;;
			* ) echo "Please answer yes or no.";;
		esac
	done
}

run_phpcs
