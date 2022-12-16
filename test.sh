#!/bin/sh

php DotMapParser.php --input=examples/spec-wars.map --output=examples/spec-wars-parsed.map

php DotMapParser.php --input=examples/smudge.map --output=examples/smudge-parsed.map --values-file=examples/values.txt
