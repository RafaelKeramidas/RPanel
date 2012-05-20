<?php
	/***
	 * RPanel - Panel functions
	 * 
	 * @Author		Rafael 'R@f' Keramidas <rafael@keramid.as>
	 * @Version		1.0
	 * @Date		18th Mai 2012
	 * @Licence		GPLv3 
	 ***/
	
	class PanelFunctions {
	
		public function rewriteURL($rewrite, $pagename) {
			$url = null;
			
			if($rewrite == true) {
				$url = $pagename . '.htm';
			}
			else {
				$url = 'index.php?p=' . $pagename;
			}
			
			return $url;
		}
	}