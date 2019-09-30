#!/bin/bash

ng build --prod

rm ../../public/$1/*.js
rm ../../public/$1/*.css
rm ../views/index.blade.php

mv dist/*/*.js ../../public/$1/
mv dist/*/*.css ../../public/$1/
mv dist/*/index.html ../views/index.blade.php

if test $1; then
sed -i -e "s/src=\"/src=\"$1\//g" -e "s/href=\"styles/href=\"$1\/styles/g" -e "s/<base href=\"\/\">/<base href=\"\/\">\n<meta name=\"csrf-token\" content=\"{{ csrf_token() }}\">/g" ../views/index.blade.php;
fi
