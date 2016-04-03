<?

class Page {

	var $title;
	var $html_header;
	var $html_body;
	var $page;
	var $site;
	var $pagetype;
	var $messages;

	var $nopagecache = false;

	var $style = array();
	var $script = array();
	var $meta = array();

//	var $data = array('header' => '', 'page' => '', 'footer' => '', 'sitemap' => '', 'sitebottom' => '', 'breadcrumb' => '', 'navigation' => '', 'content' => '');

	var $content = array();
	var $page_url_stack = array();
	var $page_slug;
	var $root_page;

	public function create() {
		global $_c;

		// Metatags
		include_once ('source/conf/meta.php');

		if ($this -> content['is_xhr'] != true)
			$this -> html_header = $this -> getHtmlHeader() . '<body>' . chr(13);

		if ($this -> content['is_xhr'] != true)
			$this -> html_end = '</body>' . chr(13) . $this -> getHtmlEnd();

		if ($this -> content['is_xhr'] != true)
			$this -> page .= $_c -> partial('sitetop', $this -> data['sitetop']);

		if ($this -> content['is_xhr'] != true)
			if ($this -> pagetype != 'splash')
				$this -> page .= $_c -> partial('header', $this -> data['header']);

		$this -> page .= $_c -> partial('page', $this -> data['page']);

		if ($this -> content['is_xhr'] != true)
			$this -> page .= $_c -> partial('footer', $this -> data['footer']);

		if ($this -> content['is_xhr'] != true)
			if ($this -> pagetype != 'splash')
				$this -> page .= $_c -> partial('sitemap', $this -> data['sitemap']);

		if ($this -> content['is_xhr'] != true)
			$this -> page .= $_c -> partial('sitebottom', $this -> data['sitebottom']);

		$this -> site = $this -> html_header . $this -> page . $this -> html_end;

	}

	// set page title
	function setTitle($s) {
		if (trim($s) != '') {
			$insert = $s . '  ';
		} else {
			$insert = '';
		}

		$this -> title = '<title>' . $insert . '</title>';
		$this -> title;
	}

	// Stylesheets hinzufügen
	function addStyle($s, $insertion = false, $remote = false) {

		$folder = '';
		if ($remote == false)
			$folder = './css/';

		if ($insertion == false) {
			$this -> style[] = '<link rel="stylesheet" type="text/css" href="' . $folder . $s . '" title="style" />';
		}
		if ($insertion == 'top') {
			$this -> style = array('<link rel="stylesheet" type="text/css" href="' . $folder . $s . '" title="style" />') + $this -> style;
		}
	}

	// Java Scripts hinzufügen
	function addScript($s, $force = false) {
		if ($force == false) {
			$this -> script[] = '<script language="javascript" type="text/javascript" src="js/' . $s . '"></script>';
		} else {
			$this -> script[] = '<script language="javascript" type="text/javascript" src="' . $s . '"></script>';
		}
	}

	// Metatag hinzufügen
	function addMeta($att_a, $cont_a, $att_b, $cont_b) {
		$this -> meta[] = '<meta ' . $att_a . '="' . $cont_a . '" ' . $att_b . '="' . $cont_b . '">' . $lb;
	}

	// konstruiere HTML Header
	function getHtmlHeader() {

		$lb = chr(13);

		$h = '<!DOCTYPE html >' . $lb;
		//PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.$lb;
		$h .= '<html xmlns="http://www.w3.org/1999/xhtml">' . $lb;
		$h .= '<head>' . $lb;
		$h .= '<base href="' . PAGE_BASE . '" />' . $lb;

		$h .= '<link href="favicon.ico" rel="shortcut icon" />' . $lb;
		//$h.= '<link rel="alternate" type="application/rss+xml" title="" href="'.PAGE_BASE.$this->language.'/rss" /></head>';

		if (!$this -> title)
			$this -> setTitle('Seitentitel nicht definiert. Benutze $_->setTitle() !');

		$h .= $this -> title . $lb;

		// adding meta tags
		foreach (array_reverse($this->meta) as $meta) {
			$h .= $meta . $lb;
		}
		// adding style sheets
		foreach (array_reverse($this->style) as $css) {
			$h .= $css . $lb;
		}
		// adding java scripts
		foreach (array_reverse($this->script) as $js) {
			$h .= $js . $lb;
		}
		$h .= '<script language="javascript" type="text/javascript">' . $lb . '<!--' . $lb . ' 	var HOME_URL = \'' . PAGE_BASE . '\';' . $lb . ' -->' . $lb . '</script>' . $lb;

		$h .= "</head>" . $lb;
		return $h;
	}

	function getHtmlBody() {
		return $this -> body;
	}

	function getHtmlEnd() {
		return '</html>' . chr(13);
	}

}
?>