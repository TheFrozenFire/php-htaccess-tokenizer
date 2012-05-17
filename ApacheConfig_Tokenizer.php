<?php
class ApacheConfig_Tokenizer {
	const LINETYPE_COMMENT = 1;
	const LINETYPE_OPENTAG = 2;
	const LINETYPE_CLOSETAG = 3;
	const LINETYPE_DIRECTIVE = 4;
	const LINETYPE_EMPTY = 5;

	public static function parse($string) {
		return self::parseLines(
			explode("\n", $string)
		);
	}
	
	public static function parseLines(&$lines) {
		$context = array();
		while(list($lineNumber, $line) = each($lines)) {
			$line = trim($line);
			switch(self::getLineType($line)) {
				case self::LINETYPE_COMMENT:
					$context[] = array(
						"type" => "comment",
						"text" => substr($line, 1)
					);
					break;
				case self::LINETYPE_OPENTAG:
					$line = trim("<>", $line);
					$tag = strstr($line, " ", true);
					$params = strstr($line, " ");
					$context[] = array(
						"type" => "tag",
						"tag" => $tag,
						"params" => $params,
						self::parseLines($lines)
					);
					break;
				case self::LINETYPE_CLOSETAG:
					return $context;
				case self::LINETYPE_DIRECTIVE:
					$type = strstr($line, " ", true);
					$params = strstr($line, " ");
					$context[] = array(
						"type" => "directive",
						"directive" => $type,
						"params" => trim($params)
					);
					break;
				case self::LINETYPE_EMPTY:
					$context[] = array(
						"type" => "empty"
					);
					break;
			}
		}
		return $context;
	}
	
	public static function getLineType($line) {
		if(empty($line))
			return self::LINETYPE_EMPTY;
		elseif($line[0] == "#")
			return self::LINETYPE_COMMENT;
		elseif($line[0] == "<" && $line[1] != "/")
			return self::LINETYPE_OPENTAG;
		elseif($line[0] == "<" && $line[1] == "/")
			return self::LINETYPE_CLOSETAG;
		else return self::LINETYPE_DIRECTIVE;
	}
}
