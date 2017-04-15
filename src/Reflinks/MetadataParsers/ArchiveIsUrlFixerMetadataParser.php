<?php
/*
	Copyright (c) 2017, Zhaofeng Li
	All rights reserved.
	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
	* Redistributions of source code must retain the above copyright notice, this
	list of conditions and the following disclaimer.
	* Redistributions in binary form must reproduce the above copyright notice,
	this list of conditions and the following disclaimer in the documentation
	and/or other materials provided with the distribution.
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
	IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
	FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
	DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
	SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
	OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
	OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*
	Archive.is URL fixer

	Per <https://en.wikipedia.org/wiki/Wikipedia_talk:Using_archive.is#RfC:_Should_we_use_short_or_long_format_URLs.3F>,
	long URLs are referred over short ones. This MetadataParser, when used in
	a chain, expands the short URLs.
*/

namespace Reflinks\MetadataParsers;

use Reflinks\MetadataParser;
use Reflinks\Metadata;
use Reflinks\Utils;

class ArchiveIsUrlFixerMetadataParser extends MetadataParser {
	const DOMAINS = array(
		"archive.is",
		"archive.fo",
	);
	public function parse( \DOMDocument $dom ) {}
	public function chain( \DOMDocument $dom, Metadata &$metadata ) {
		if ( !isset( $metadata->url ) ) {
			return;
		}

		$parsed = parse_url( $metadata->url );
		if ( !in_array( $parsed['host'], self::DOMAINS ) ) {
			return;
		}

		$xpath = Utils::getXpath( $dom );
		$nodes = $xpath->query( "//x:input[@id='SHARE_LONGLINK']" );
		if ( $nodes->length ) {
			$metadata->url = trim( $nodes->item( 0 )->attributes->getNamedItem( "value" )->nodeValue );
		}
	}
}
