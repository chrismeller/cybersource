<?php

function formatXml($xml) {

	$dom = new DOMDocument();

	// Initial block (must before load xml string)
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	// End initial block

	$dom->loadXML($xml);

	return $dom->saveXML();
}

// EOL