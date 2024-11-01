<?php
class Wp_Dxp_Block_Renderer {

	/**
	 * Runtime WP DXP rule object cache
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $rules    Array of rules objects
	 */
	private static $rules = [];

	/**
	 * Render block based on WP DXP rule
	 * @param  string $block_content
	 * @param  array $block
	 * @return string
	 */
	public static function render($block_content, $block, $instance)
	{
	    if ( !empty($block['attrs']['wpDxpRule']) && !empty($block['attrs']['wpDxpId'])){
	    
	    	$placeholder = false;
	    
	    	// have we received blocks to render?
	    	if(!empty($GLOBALS['WP_DXP_BLOCKS'])){
	    		if(!in_array($GLOBALS['WP_DXP_POST_ID'].'-'.$block['attrs']['wpDxpId'], $GLOBALS['WP_DXP_BLOCKS'])){
	    			$placeholder = true;
	    		}
	    	}
	    	
	    	// no, just a normal request
	    	else $placeholder = true;

	     	// should we insert a placeholder tag?
	     	if ( $placeholder ) {
		        $rules = self::getRules($block['attrs']['wpDxpRule']);

				$attr = '';

				if ( $rules ) {
					foreach ( $rules as $rule ) {
						foreach($rule->tagAttributes(empty($block['attrs']['wpDxpAction']) ? 'show' : $block['attrs']['wpDxpAction']) as $k=>$v){
							$attr .= ' '.$k.'="'.htmlentities($v).'"';
						}
					}
				}

				return '<'.WP_DXP_TAG.' post-id="'.(!empty($GLOBALS['WP_DXP_POST_ID']) ? $GLOBALS['WP_DXP_POST_ID'] : get_the_ID()).'" block-id="'.$block['attrs']['wpDxpId'].'"'.$attr.'></'.WP_DXP_TAG.'>';

			}

	    }

	    return $block_content;
	}

	
	/**
	 * Intercept wp_trim_words to preserve WP DXP blocks
	 * @param  string $text
	 * @param  int $num_words
	 * @param  string $more
	 * @param  string $original_text
	 * @return string
	 */
	public static function trim_words($text, $num_words, $more, $original_text){

		// do we have a group of slots that need to post-processing?
		if(preg_match_all('~<'.WP_DXP_TAG.' post-id="([0-9]+)" block-id="([0-9]+)"([^>]*)></'.WP_DXP_TAG.'>~',$original_text,$m)){

			$text = $original_text;
			$slots = [];

			$index = 0;

			// add slots for the sub-blocks
			foreach($m[0] as $i => $tag){
			
				$skip = false;
			
				// check additional attributes
				if($m[3][$i]){
					foreach(preg_split('~( "?|"$)~',$m[3][$i]) as $attr){
						$attr = explode('="',$attr);
						
						// do we have a delayed attribute?
						if($attr[0] == 'delayed' || $attr[0] == 'lifetime'){
							// leave out delayed blocks from trimmed content
							$text = str_replace($tag, '<'.WP_DXP_TAG.' slot="'.$i.'"></'.WP_DXP_TAG.'>', $text);
							$skip = true;
							break;
						}
					}
				}

				$text = str_replace($tag, $skip ? '' : '<'.WP_DXP_TAG.' slot="'.$index.'"></'.WP_DXP_TAG.'>', $text);

				// add to slots collection
				if(!$skip)			
					$slots[$index++] = [intval($m[1][$i]),intval($m[2][$i])];
					
			}
			
			return '<'.WP_DXP_TAG.' slots="'.json_encode($slots).'" template="'.htmlentities($text).'" filters="trim_words"></'.WP_DXP_TAG.'>';
		}
		
		return $text;
	}
	
	private static function getRule($id){
		
		// fetch and cache the rule and parse
		if(!array_key_exists($id, self::$rules)){
			self::$rules[$id] = Wp_Dxp_Rule::find($id);
		}
		
		return self::$rules[$id];
	}

	private static function getRules($id) {
		$id = array_unique(array_filter(explode(',', $id)));

		if ( empty($id) ) {
			return false;
		}

		$missingId = [];

		foreach ( $id as $i ) {
			if ( !array_key_exists($i, self::$rules) ) {
				$missingId[] = $i;
			}
		}

		if ( !empty($missingId) ) {
			$rules = Wp_Dxp_Rule::findAll(implode(',', $missingId));

			if ( $rules ) {
				foreach ( $rules as $rule ) {
					if ( $rule ) {
						self::$rules[$rule->id] = $rule;
					}
				}
			}
		}

		$rules = [];
		foreach ( $id as $i ) {
			if ( isset(self::$rules[$i]) ) {
				$rules[] = self::$rules[$i];
			}
		}

		return $rules;
	}
	
}