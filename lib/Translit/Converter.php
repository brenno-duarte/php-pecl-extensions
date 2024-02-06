<?php

namespace PeclPolyfill\Translit;

class Converter {
	const JUMP_MAP = 1;
	const JUMP_EXPAND = 2;
	const JUMP_REMOVE = 3;
	const JUMP_TRANSPOSE_UP = 4;
	const JUMP_TRANSPOSE_DOWN = 5;

	public function __construct($filename, $directory) {
		$this->filename = $filename;
		$this->directory = $directory;
		$this->function_name = NULL;
		$this->allow_override = false;

		$this->aliases = array();
		$this->jumptbl = array();
		$this->map = array();
		$this->expand = array();
		$this->expand_max_length = 0;
		$this->transpose = array();
		$this->use_map = true;
		$this->skip = false;
	}

	private function register_jump($cp, $jump_type)
	{
		$block = (int) ($cp / 256);
		if (!isset ($this->jumptbl[$block])) {
			$this->jumptbl[$block] = array_fill(0, 256, 0);
		}

		if (isset($this->jumptpl[$block][$cp % 256]) && $this->jumptbl[$block][$cp % 256] && !$this->allow_override) {
			return false;
		}

		$this->jumptbl[$block][$cp % 256] = $jump_type;
		return true;
	}

	private function register_map($cp, $res_nr)
	{
		$block = (int) ($cp / 256);
		if ($this->register_jump($cp, self::JUMP_MAP)) {
			if (!isset ($this->map[$block])) {
				$this->map[$block] = array_fill(0, 256, 0);
			}
			$this->map[$block][$cp % 256] = $res_nr;
		} else {
			echo "Code point $cp is already defined.\n";
		}
	}

	private function register_remove($cp)
	{
		$block = (int) ($cp / 256);
		if (!$this->register_jump($cp, self::JUMP_REMOVE)) {
			echo "Code point $cp is already defined.\n";
		} else {
			$this->skip = true;
		}
	}

	private function register_expand($cp, $res_nrs)
	{
		if (count($res_nrs) == 0) {
			$this->register_remove($cp);
			return;
		}
		if (count($res_nrs) == 1 and $this->use_map) {
			$this->register_map($cp, $res_nrs[0]);
			return;
		}
		$block = (int) ($cp / 256);
		if ($this->register_jump($cp, self::JUMP_EXPAND)) {
			if (!isset ($this->expand[$block])) {
				$this->expand[$block] = array_fill(0, 256, 0);
			}
			if ((!isset($this->expand_max_length)) or (count($res_nrs) > $this->expand_max_length)) {
				$this->expand_max_length = count($res_nrs);
			}
			$this->expand[$block][$cp % 256] = array(count($res_nrs), $res_nrs);
		} else {
			echo "Code point $cp is already defined.\n";
		}
	}

	private function register_transpose($cp, $res_nrs, $type)
	{
		$block = (int) ($cp / 256);
		if ($this->register_jump($cp, $type)) {
			if (!isset ($this->transpose[$block])) {
				$this->transpose[$block] = array_fill(0, 256, 0);
			}
			$this->transpose[$block][$cp % 256] = $res_nrs[0];
		} else {
			echo "Code point $cp is already defined.\n";
		}
	}
	
	private function register_transpose_up($cp, $res_nrs)
	{
		$this->register_transpose($cp, $res_nrs, self::JUMP_TRANSPOSE_UP);
	}
	
	private function register_transpose_down($cp, $res_nrs)
	{
		$this->register_transpose($cp, $res_nrs, self::JUMP_TRANSPOSE_DOWN);
	}

	private function generate_map($table)
	{
		$txt = '';
		$width = ceil(log(max($table), 10));
		$txt .= "\t\t[\n";
		for ($i = 0; $i < 256; $i++) {
			if ($i % 16 == 0) {
				$txt .= "\t\t\t";
			}
			if (!isset($table[$i])) {
				$table[$i] = 0;
			}
			$txt .= sprintf("%{$width}d", $table[$i]);
			if ($i % 16 == 15) {
				$txt .= ",\n";
			} else {
				$txt .= ", ";
			}
		}
		$txt .= "\t\t],\n";
		return $txt;
	}

	private function generate_md_map($table)
	{
		$txt = '';
		$txt .= "\t\t[\n";
		for ($i = 0; $i < 256; $i++) {
			if ($i % 8 == 0) {
				$txt .= "\t\t\t";
			}
			if (!isset($table[$i]) || !is_array($table[$i])) {
				$table[$i] = array(0, array(0));
			}
			$txt .= "[". $table[$i][0]. ", ". join(", ", $table[$i][1]). "]";
			if ($i % 8 == 7) {
				$txt .= ",\n";
			} else {
				$txt .= ", ";
			}
		}
		$txt .= "\t\t],\n";
		return $txt;
	}

	private function generate_code_header() {
		$txt = <<<ENDHEADER
<?php

/*
 * Warning: Do not edit!
 * This file is generated from a transliteration definition table with the name
 * "{$this->filename}".
 */

namespace PeclPolyfill\Translit;


ENDHEADER;
		return $txt;
	}

	private function generate_code($function_name, $aliases, $jumps, $map, $expand, $expand_max_length, $transpose)
	{
		$class_name = str_replace(' ', '', ucwords(strtr($function_name, '_', ' ')));
		$txt = "class $class_name {\n";
		
		$rev_jump = array();
		$table_definition = '';
		/* Generate jump table */
		$c = 0;
		if (count($jumps)) {
			$table_definition .= "\tprivate static \$jump_table = [\n";
			foreach ($jumps as $block => $data)
			{
				$table_definition .= $this->generate_map($data);
				$rev_jump[$c] = $block;
				$c++;
			}
			$table_definition .= "\t];\n\n";
		}


		$rev_map = array();
		/* Generate map table */
		$c = 0;
		if (count($map)) {
			$table_definition .= "\tprivate static \$map_table = [\n";
			foreach ($map as $block => $data)
			{
				$table_definition .= $this->generate_map($data);
				$rev_map[$block] = $c;
				$c++;
			}
			$table_definition .= "\t];\n\n";
		}


		$rev_expand = array();
		/* Generate expand table */
		$c = 0;
		if (count($expand)) {
			$table_definition .= "\tprivate static \$expand_table = [\n";
			foreach ($expand as $block => $data)
			{
				$table_definition .= $this->generate_md_map($data);
				$rev_expand[$block] = $c;
				$c++;
			}
			$table_definition .= "\t];\n\n";
		}

		$rev_transpose = array();
		/* Generate transpose table */
		$c = 0;
		if (count($transpose)) {
			$table_definition .= "\tprivate static \$transpose_table = [\n";
			foreach ($transpose as $block => $data)
			{
				$table_definition .= $this->generate_map($data);
				$rev_transpose[$block] = $c;
				$c++;
			}
			$table_definition .= "\t];\n\n";
		}

		$txt .= $table_definition;

		$txt .= <<<ENDCODE
	public static function convert(array \$in)
	{
		/* Determine initial string length */
		\$in_length = count(\$in);
		\$out = [];

		/* Loop over input array */
		for (\$i = 0; \$i < \$in_length; \$i++) {
			\$block = \$in[\$i] >> 8;
			\$cp    = \$in[\$i] & 255;

			\$no_jump = 0;
			switch (\$block) {
ENDCODE;
		foreach ($rev_jump as $map_id => $block) {
			$txt .= "\n\t\t\t\tcase $block: \$jump_map = self::\$jump_table[$map_id]; ";
			if (isset($map[$block])) {
				$id = $rev_map[$block];
				$txt .= "\$replace_map = self::\$map_table[$id]; ";
			}
			if (isset($expand[$block])) {
				$id = $rev_expand[$block];
				$txt .= "\$expand_map = self::\$expand_table[$id]; ";
			}
			if (isset($transpose[$block])) {
				$id = $rev_transpose[$block];
				$txt .= "\$transpose_map = self::\$transpose_table[$id]; ";
			}
			$txt .= "break;";
		}

		$txt .= <<<ENDCODE

				default: \$no_jump = 1;
			}
			if (\$no_jump) {
				\$jump = 0;
			} else {
				\$jump = \$jump_map[\$cp];
			}

			switch (\$jump) {
				case 0: /* No changes */
					\$out[] = \$in[\$i];
					break;

ENDCODE;
		if (count($map)) {
			$txt .= <<<ENDCODE
				case 1: /* Simple mapping */
					\$out[] = \$replace_map[\$cp];
					break;

ENDCODE;
		}
		if (count($expand)) {
			$txt .= <<<ENDCODE
				case 2: /* Expand to more than one char */
					for (\$j = 1; \$j <= \$expand_map[\$cp][0]; \$j++) {
						\$out[] = \$expand_map[\$cp][\$j];
					}
					break;

ENDCODE;
		}
		if ($this->skip) {
			$txt .= <<<ENDCODE
				case 3: /* Skip */
					break;

ENDCODE;
		}
		if (count($transpose)) {
			$txt .= <<<ENDCODE
				case 4: /* Transpose Up */
					\$out[] = \$in[\$i] + \$transpose_map[\$cp];
					break;
				case 5: /* Transpose Down */
					\$out[] = \$in[\$i] - \$transpose_map[\$cp];
					break;

ENDCODE;
		}
		$txt .= <<<ENDCODE
			}
		}
		return \$out;
	}
}

ENDCODE;
		/* Create file and fileheader */
		$fp = fopen($this->directory.'/'.$class_name.'.php', 'w');
		fwrite($fp, $this->generate_code_header());
		fwrite($fp, $txt);
		fclose($fp);
	
		$include = fopen($this->directory.'/Translit.php', "a");
		$fq_class_name = 'Kirra\Polyfill\Translit\\'.$class_name;
		fputs($include, "\t\t".var_export($function_name, true)." => [".var_export($fq_class_name, true).", 'convert'],\n");
		foreach ($aliases as $alias) {
			fputs($include, "\t\t".var_export($alias, true)." => [".var_export($fq_class_name, true).", 'convert'],\n");
		}
		fclose($include);
	}
	
	public function convert() {
		$lines = file($this->filename);
		foreach ($lines as $line) {
			if (preg_match("/^#pragma\s+(.*)$/", $line, $match)) {
				$setting = trim($match[1]);
				if ($setting == 'NOMAP') {
					$this->use_map = false;
				} else {
					list($setting, $value) = preg_split("/\s+/", $setting);
					switch ($setting) {
						case 'OVERRIDE_ALLOWED':
							$this->allow_override = ($value == '1');
							break;
						case 'ALIAS':
							$this->aliases[] = $value;
							break;
						case 'INCLUDE':
							if (isset($filters[$value])) {
								list($o_jumptbl, $o_map, $o_expand, $o_transpose, $o_expand_max_length) = $filters[$value];
								if ($o_expand_max_length > $this->expand_max_length) {
									$this->expand_max_length = $o_expand_max_length;
								}
								foreach ($o_jumptbl as $block => $values)
									foreach ($values as $id => $cp)
										if ($cp != 0)
											$this->jumptbl[$block][$id] = $cp;
								foreach ($o_map as $block => $values)
									foreach ($values as $id => $cp)
										if ($cp != 0)
											$this->map[$block][$id] = $cp;
								foreach ($o_expand as $block => $values)
									foreach ($values as $id => $cp)
										if ($cp != 0)
											$this->expand[$block][$id] = $cp;
								foreach ($o_transpose as $block => $values)
									foreach ($values as $id => $cp)
										if ($cp != 0)
											$this->transpose[$block][$id] = $cp;

							} else {
								echo "Can not include filter '$value' as it does not exist (yet)\n";
							}
							break;
					}
				}

			} else
			if (preg_match("/^([a-z_]+):$/", $line, $match)) {
				if ($this->function_name) {
					echo "Writing code for $this->function_name\n";
					$code = $this->generate_code($this->function_name, $this->aliases, $this->jumptbl, $this->map, $this->expand, $this->expand_max_length, $this->transpose);
					$filters[$this->function_name] = array($this->jumptbl, $this->map, $this->expand, $this->transpose, $this->expand_max_length);
					$this->jumptbl = $this->map = $this->expand = $this->transpose = $this->aliases = array();
					$this->expand_max_length = 0;
					$this->use_map = true;
					$this->override_allowed = false;
				}
				$this->function_name = $match[1];
				echo "New private function: $this->function_name\n";
			} else
			if (preg_match('/^(.*?)([+-]?[>=])([^#]*)/', $line, $match)) {
				$def = str_replace(' ', '', $match[1]);
				$res = str_replace(' ', '', $match[3]);

				switch ($match[2]) {
					case '>':
					case '=':
						$func = "register_expand";
						break;
					case '+=':
						$func = "register_transpose_up";
						break;
					case '-=':
						$func = "register_transpose_down";
						break;
				}

				if (preg_match("/^(U\+[0-9A-F]{4})(,U\+[0-9A-F]{4})*$/", $res, $match)) {
					$res_nrs   = array();
					foreach (split(',', preg_replace('/U\+/', '', $match[0])) as $cp) {
						$res_nrs[] = hexdec($cp);
					}
				} else
				if (preg_match('/^U\+([0-9A-F]{4})-U\+([0-9A-F]{4})$/', $res, $match)) {
					$res_begin = hexdec($match[1]);
					$res_end   = hexdec($match[2]);
					$res_nrs   = array();
					for ($i = $res_begin; $i <= $res_end; $i++) {
						$res_nrs[] = $i;
					}
				} else
				if (preg_match('/^U\+([0-9A-F]{4})$/', $res, $match)) {
					$res_nrs = array(hexdec($match[1]));
				} else
				if (preg_match('/^"(.*)"$/', $res, $match)) {
					$res_nrs = array();
					foreach (preg_split('//', stripslashes($match[1])) as $char) {
						if ($char !== '') {
							$res_nrs[] = ord($char);
						}
					}
				}

				if (preg_match("/^(Even|Odd)U\+([0-9A-F]{2})([0-9A-F]{2})-U\+([0-9A-F]{2})([0-9A-F]{2})$/", $def, $m)) {
					$begin = hexdec($m[2].$m[3]);
					$end   = hexdec($m[4].$m[5]);
					for ($i = $begin; $i <= $end; $i++) {
						if ($m[1] == "Even" and ($i % 2) == 0) {
							$this->$func($i, $res_nrs);
						}
						if ($m[1] == "Odd" and ($i % 2) == 1) {
							$this->$func($i, $res_nrs);
						}
					}
				} else if (preg_match("/^U\+([0-9A-F]{2})([0-9A-F]{2})-U\+([0-9A-F]{2})([0-9A-F]{2})$/", $def, $m)) {
					$begin = hexdec($m[1].$m[2]);
					$end   = hexdec($m[3].$m[4]);
					for ($i = $begin; $i <= $end; $i++) {
						$this->$func($i, $res_nrs);
					}
				} else if (preg_match("/^(U\+[0-9A-F]{4})(,U\+[0-9A-F]{4})*$/", $def, $m)) {
					foreach (split(',', preg_replace('/U\+/', '', $m[0])) as $cp) {
						$this->$func(hexdec($cp), $res_nrs);
					}
				}
			}
		}

		$code = $this->generate_code($this->function_name, $this->aliases, $this->jumptbl, $this->map, $this->expand, $this->expand_max_length, $this->transpose);
	}
}
