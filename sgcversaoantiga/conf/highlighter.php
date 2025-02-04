<?php

/**
 * Highlighter - PHP Class to highlight code blocks and add some extra tags
 *
 * Tested and working on PHP 4.3.2 and higher
 *
 * LICENSE: This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @author       Giulio Bai <slide.wow@gmail.com>
 * @copyright   (C)2007 Giulio Bai - GNU GPL
 * @license      http://www.gnu.org/licenses/gpl.html GNU General public License
 * @version      1.0
 * @link            http://hewle.com/
 */
 

/**
 * A class to highlight blocks of php code and add personalized tags to them.
 * 
 * Example of use:
 * The following use will add the <box> tag to the text and highligh it using
 * php's default colors.
 * Then it will link all the functions to their page on the php manual
 * <code>
 * include_once('highlighter.php');
 *
 * $highlight = new Highlighter();
 * $code = $highlight->add_tags($code, "box");
 *
 * echo $code;
 * </code>
 *
 * @author      Giulio Bai <slide.wow@gmail.com>
 * @copyright  (C)2007 Giulio Bai
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @version     1.0
 * @link        http://hewle.com
 */
class Highlighter
{
	
	/**
	 * Logs if the begin php tag is present (1) or not (0)
	 *
	 * @var bool
	 */
	var $begin_tag = 1;
	
	/**
	 * Logs if the end php tag is present (1) or not (0)
	 *
	 * @var bool
	 */
	var $end_tag = 1;
	
	
	/**
     * Adds the specified tag to the code block
     *
     *
	 * Example 1: 
	 * If the code passed to the method was
	 * <code>
	 * $code = "Hello World!";
	 * add_tags($code, "boxed", 1);
	 * </code>
	 * The returning code, returned by the function would be (in HTML)
	 * <code>
	 * <div class="boxed">Hello World!</div>
	 * </code>
	 *
	 * Example 2:
	 * If the code passed to the method was
	 * <code>
	 * $code = "Hello World!";
	 * add_tags($code, "boxed-class", 0);
	 * </code>
	 * Thereturning text would be (in HTML)
	 * <code>
	 * <boxed-class>Hello World!</boxed-class>
	 * </code>
	 *
     * @param string $text  the code to parse
     * @param string $type the code tag to add
	 * @param bool   $div switch beetwen divs and "pure-tags"
	 *
	 * @return string the new text, tagged!
     */
    function add_tags($text, $ype = "code", $div = 0)
    {
		if ($div) {
			$tagl = "div class=\"$type\"";
			$tagr = "div";
		} else
			$tagl = $tagr = $type;	
		
        return "<$tagl>" . nl2br(htmlentities($text)) . "</$tagr>";
		
    }
	
	
	/**
     * Highlights the code and links functions to their page on php.net
	 *
     * @param string $text  the code to highlight
	 *
	 * @return string the code, highlighted
     */	
	function highlight($text)
    {
        ob_start();
		
        highlight_string($text);
        $code = ob_get_contents();
		
        ob_end_clean();

        $color= ini_get("highlight.keyword");
        $manual = "http://www.php.net/manual-lookup.php?lang=en&amp;pattern=";

        $code = preg_replace( '{([\w_]+)(\s*</font>)' . '(\s*<font\s+color="' . $color . '">\s*\()}m',
					'<a class="code" title="Further info about $1" href="' . $manual . '$1">$1</a>$2$3', $code);

		if(check_be_tags($code) == 0) $code = add_be_tags($code);
		
        return $code;
    }

	/**
	 * Checks if the begin and end tags are present in the code block
	 * 
	 * @param string $code the code
	 * 
	 * @return bool returns TRUE if tags are present, else FALSE
	 */
    function check_be_tags($code)
	{
        $code = trim($code);
		
        if (strpos($code, '<?') === 0) {
            $this->begin_tag = 0;
			
			return 0;
		}
		
        if (strpos($code,  '?>') === 0) {
			$this->end_tag = 0;
			
			return 0;
		}

        return 1;
    }
	
	
	/**
	 * Adds the begin and end tags to a code block
	 *
	 * @param string $code the code
	 *
	 * @return string the code with tags
	 */
	function add_be_tags($code)
	{
		$code = trim($code);
		
        if ($this->begin_tag == 0) {
            $code = "<?php\n" . $code;
			$this->begin_tag = 1;
		}
		
        if ($this->end_tag == 0) {
			$code .= "\n?>";
			$this->end_tag = 1;
		}
			
		return $code;
	}

}

?> 