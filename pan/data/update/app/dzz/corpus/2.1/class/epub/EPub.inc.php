<?php
/**
 * Create an ePub compatible book file.
 *
 * Please note, once finalized a book can no longer have chapters of data added or changed.
 *
 * License: GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 * 
 * Thanks to: Adam Schmalhofer and Kirstyn Fox for invaluable input and for "nudging" me in the right direction :)
 *
 * @author A. Grandt
 * @copyright A. Grandt 2009-2011
 * @license GNU LGPL, Attribution required for commercial implementations, requested for everything else.
 * @version 2.04
 * @link http://www.phpclasses.org/package/6115
 * @uses Zip.php version 1.23; http://www.phpclasses.org/browse/package/6110.html
 */
class EPub {
	const VERSION = 2.04;
	const REQ_ZIP_VERSION = 1.23;

	const IDENTIFIER_UUID = 'UUID';
	const IDENTIFIER_URI = 'URI';
	const IDENTIFIER_ISBN = 'ISBN';

	/** Ignore all external references, and do not process the file for these */
	const EXTERNAL_REF_IGNORE = 0;
	/** Process the file for external references and add them to the book */
	const EXTERNAL_REF_ADD = 1;
	/** Process the file for external references and add them to the book, but remove images, and img tags */
	const EXTERNAL_REF_REMOVE_IMAGES = 2;
	/** Process the file for external references and add them to the book, but replace images, and img tags with [image] */
	const EXTERNAL_REF_REPLACE_IMAGES = 3;

	public $maxImageWidth = 200000;
	public $maxImageHeight = 200000;

	private $splitDefaultSize = 250000;

    private $indexChapter = 0;

	private $zip;

	private $title = "";
	private $language = "zh";
	private $identifier = "";
	private $identifierType = "";
	private $description = "";
	private $author = "";
	private $authorSortKey = "";
	private $publisherName = "";
	private $publisherURL = "";
	private $date = 0;
	private $rights = "";
	private $subject = "";
	private $coverage = "";
	private $relation = "";
	private $sourceURL = "";

	private $chapterCount = 0;
	private $opf_manifest = "";
	private $opf_spine = "";
	private $ncx_navmap = "";
	private $opf = "";
	private $ncx = "";
	private $isFinalized = FALSE;
	private $isCoverImageSet = FALSE;

	private $fileList = array();

	private $dateformat = 'Y-m-d\TH:i:s.000000P'; // ISO 8601 long
	private $dateformatShort = 'Y-m-d'; // short date format to placate ePubChecker.
	private $headerDateFormat = "D, d M Y H:i:s T";

	protected $isGdInstalled;
	private $docRoot = NULL;
	
	private $EPubMark = TRUE;
	private $generator = "";
    private $csstmpfile = '';
	private $cmk = 1;//??????????????????

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	function __construct($cmk) {
		include_once("Zip.php");
		if (!defined("Zip::VERSION") || Zip::VERSION < self::REQ_ZIP_VERSION) {
			die("<p>EPub requires Zip.php at version " . self::REQ_ZIP_VERSION . " or higher.<br />You can obtain the latest version from <a href=\"http://www.phpclasses.org/browse/package/6110.html\">http://www.phpclasses.org/browse/package/6110.html</a>.</p>");
		}
		include_once("EPubChapterSplitter.inc.php");
		$this->cmk=$cmk;
		$this->docRoot = $_SERVER["DOCUMENT_ROOT"] . "/";

		$this->zip = new Zip();
		$this->zip->addFile("application/epub+zip", "mimetype");
		$this->zip->addDirectory("META-INF/");
        if($cmk == 1) $fullpath ='OEBPS/content.opf';
        else $fullpath ='content.opf';
		$this->content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<container version=\"1.0\" xmlns=\"urn:oasis:names:tc:opendocument:xmlns:container\">\n\t<rootfiles>\n\t\t<rootfile full-path=\"".$fullpath."\" media-type=\"application/oebps-package+xml\" />\n\t</rootfiles>\n</container>\n";

		$this->zip->addFile($this->content, "META-INF/container.xml");
		$this->content = NULL;
		$this->opf_manifest = "\t\t<item id=\"ncx\" href=\"toc.ncx\" media-type=\"application/x-dtbncx+xml\" />\n";
		$this->chapterCount = 0;

		$this->isGdInstalled = extension_loaded('gd') && function_exists('gd_info');

	}

	/**
	 * Class destructor
	 *
	 * @return void
	 */
	function __destruct() {
		$this->zip = NULL;
		$this->title = "";
		$this->author = "";
		$this->publisher = "";
		$this->publishDate = 0;
		$this->bookId = "";
		$this->opf_manifest = "";
		$this->opf_spine = "";
		$this->ncx_navmap = "";
		$this->opf = "";
		$this->ncx = "";
		$this->chapterCount = 0;
		$this->subject = "";
		$this->coverage = "";
		$this->relation = "";
		$this->generator = "";
	}

	/**
	 *
	 * @param String $fileName Filename to use for the file, must be unique for the book.
	 * @param String $fileId Unique identifier for the file.
	 * @param String $fileData File data
	 * @param String $mimetype file mime type
	 * @return bool $success
	 */
	function addFile($fileName, $fileId,  $fileData, $mimetype) {
		if ($this->isFinalized || array_key_exists($fileName, $this->fileList)) {
			return FALSE;
		}
		$fileName = preg_replace('#\\\#i', "/", $fileName);
		$fileName = preg_replace('#^[/\.]+#i', "", $fileName);
		$this->zip->addFile($fileData, $fileName);
		$this->fileList[$fileName] = $fileName;
		$this->opf_manifest .= "\t\t<item id=\"" . $fileId . "\" href=\"" . $fileName . "\" media-type=\"" . $mimetype . "\" />\n";
		return TRUE;
	}

	/**
	 * Add a CSS file to the book.
	 *
	 * @param String $fileName Filename to use for the CSS file, must be unique for the book.
	 * @param String $fileId Unique identifier for the file.
	 * @param String $fileData CSS data
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? See documentation for <code>processCSSExternalReferences</code> for explanation. Default is EPub::EXTERNAL_REF_IGNORE.
	 * @param String $baseDir Default is "", meaning it is pointing to the document root. NOT used if $externalReferences is set to EPub::EXTERNAL_REF_IGNORE.
	 *
	 * @return bool $success
	 */
	function addCSSFile($fileName, $fileId,  $fileData, $externalReferences = EPub::EXTERNAL_REF_IGNORE, $baseDir = "") {
		if ($this->isFinalized || array_key_exists($fileName, $this->fileList)) {
			return FALSE;
		}
		$fileName = preg_replace('#\\\#i', "/", $fileName);
		$fileName = preg_replace('#^[/\.]+#i', "", $fileName);

		$cssDir = pathinfo($fileName);
		$cssDir = preg_replace('#^[/\.]+#i', "", $cssDir["dirname"] . "/");
		if (!empty($cssDir)) {
			$cssDir = preg_replace('#[^/]+/#i', "../", $cssDir);
		}

		if ($externalReferences !== EPub::EXTERNAL_REF_IGNORE) {
			$this->processCSSExternalReferences($fileData, $externalReferences, $baseDir, $cssDir);
		}
		if($this->cmk == 1){
            $href = 'Styles/'.$fileName;
            $fileName = 'OEBPS/Styles/'.$fileName;
        }else{
            $href = $fileName;
            $fileName = $fileName;
        }
        if(in_array($fileName, $this->fileList)){
            return false;
        }
        $this->processCSSExternalReferences($fileData, EPub::EXTERNAL_REF_ADD, "","");
		$this->zip->addFile($fileData, $fileName);
		$this->fileList[$fileName] = $fileName;
		$this->opf_manifest .= "\t\t<item id=\"css_" . $fileId . "\" href=\"" . $href . "\" media-type=\"text/css\" />\n";
		return TRUE;
	}

	/**
	 * Add a chapter to the book, as a chapter should not exceed 250kB, you can parse an array with multiple parts as $chapterData.
	 * These will still only show up as a single chapter in the book TOC.
	 *
	 * @param String $chapterName Name of the chapter, will be use din the TOC
	 * @param String $fileName Filename to use for the chapter, must be unique for the book.
	 * @param String $chapter Chapter text in XHTML or array $chapterData valid XHTML data for the chapter. File should NOT exceed 250kB.
	 * @param Bool   $autoSplit Should the chapter be split if it exceeds the default split size? Default=FALSE, only used if $chapterData is a String.
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? See documentation for <code>processChapterExternalReferences</code> for explanation. Default is EPub::EXTERNAL_REF_IGNORE.
	 * @param String $baseDir Default is "", meaning it is pointing to the document root. NOT used if $externalReferences is set to EPub::EXTERNAL_REF_IGNORE.
	 * @return bool $success
	 */
    function addChapter($chapterName, $fileName, $chapterData, $autoSplit = true, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $toToc = true) {
        if ($this->isFinalized) {
            return FALSE;
        }
		
        //$time_start = microtime(true);
        $fileName = preg_replace('#\\\#i', "/", $fileName);
        $fileName = preg_replace('#^[/\.]+#i', "", $fileName);

        $htmlDir = pathinfo($fileName);
        $htmlDir = preg_replace('#^[/\.]+#i', "", $htmlDir["dirname"] . "/");

        $chapter = $chapterData;
        if ($autoSplit && is_string($chapterData) && mb_strlen($chapterData) > $this->splitDefaultSize) {
            $splitter = new EPubChapterSplitter();

            $chapterArray = $splitter->splitChapter($chapterData);
            if (count($chapterArray) > 1) {
                $chapter = $chapterArray;
            }
        }
        if (!empty($chapter) && is_string($chapter)) {
            if ($externalReferences !== EPub::EXTERNAL_REF_IGNORE) {
                $this->processChapterExternalReferences($chapter, $externalReferences, $baseDir, $htmlDir);
            }
            $href = 'Text/'.$fileName;
            $fileName = 'OEBPS/Text/'.$fileName;
            $this->zip->addFile($chapter, $fileName);
            $this->fileList[$fileName] = $fileName;
            $this->chapterCount++;
            $this->opf_manifest .= "\t\t<item id=\"html" . $this->chapterCount . "\" href=\"" . $href . "\" media-type=\"application/xhtml+xml\" />\n";
            $this->opf_spine .= "\t\t<itemref idref=\"html" . $this->chapterCount . "\" />\n";
            if ($toToc == true) $this->ncx_navmap .= "\n\t\t<navPoint id=\"chapter" . $this->chapterCount . "\" playOrder=\"" . $this->chapterCount . "\">\n\t\t\t<navLabel><text>" . $chapterName . "</text></navLabel>\n\t\t\t<content src=\"" . $fileName . "\" />\n\t\t</navPoint>\n";
        } else if (!empty($chapter) && is_array($chapter)) {
            $partCount = 0;
            $this->chapterCount++;
         /*  $this->indexChapter++;
            if($this->indexChapter == 4){
                $start = microtime(true);
            }*/
            $pfid = $chapter['pfid'];
            foreach($chapter as $k=>$v){
                if(is_array($v)){
                    if ($externalReferences !== EPub::EXTERNAL_REF_IGNORE) {
                        $v['content'] = preg_replace('/\/\*.*?\*\//','',$v['content']);
                        $this->processChapterExternalReferences($v['content'], $externalReferences, $baseDir,'');
                    }
                    $partCount++;
                    if($partCount == 1){
                        $hrefs = ($this->cmk == 1) ? 'Text/'.$v['name']:$v['name'];
                    }
                    $endFileName =($this->cmk == 1) ? 'OEBPS/Text/'.$v['name']:$v['name'];
                    $href = ($this->cmk == 1) ? 'Text/'.$v['name']:$v['name'];
                    $v['content'] = str_replace('&#13;','',$v['content']);
                    $this->zip->addFile($v['content'], $endFileName);
                    $this->fileList[$endFileName] = $endFileName;
                    $this->opf_manifest .= "\t\t<item id=\"html" . $this->chapterCount . "-" . $partCount . "\" href=\"" . $href  . "\" media-type=\"application/xhtml+xml\" />\n";
                    if(preg_match('/imageCover_/',$endFileName)){
                        $this->opf_spine .= "\t\t<itemref idref=\"html" . $this->chapterCount . "-" . $partCount . "\"   properties=\"duokan-page-fullscreen\" />\n";
                    }else{
                        $this->opf_spine .= "\t\t<itemref idref=\"html" . $this->chapterCount . "-" . $partCount . "\"  />\n";
                    }
                }
            }
            $fileName = preg_replace("/\.xhtml$/i",'',$fileName);
            if ($toToc == true) {
                $addnac = "<navLabel><text>" . $fileName . "</text></navLabel><content src=\"" . $hrefs . "\" />";
                $navDoc = new DOMDocument();
                @$navDoc->loadHTML('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><title></title></head><body>'.$this->ncx_navmap.'</body></html>');

                  if($idnode = $navDoc->getElementById($pfid)){
                      $node =$navDoc->createElement('navPoint');
                      $node->setAttribute('id',$chapterName);
                      $node->setAttribute('pfid',$pfid);
                      $node->setAttribute('playOrder',$this->chapterCount);
                      $node->nodeValue = $addnac;
                      $idnode->appendChild($node);
                      $this->ncx_navmap =  htmlspecialchars_decode($navDoc->saveHTML());
                  }else{
                      $node =$navDoc->createElement('navPoint');
                      $node->setAttribute('id',$chapterName);
                      $node->setAttribute('pfid',$pfid);
                      $node->setAttribute('playOrder',$this->chapterCount);
                      $node->nodeValue = $addnac;
                      if($body = $navDoc->getElementsByTagName('body')->item(0)){
                          $body->appendChild($node);
                      }else{
                          $navDoc->appendChild($node);
                      }
                      $navstr = htmlspecialchars_decode($navDoc->saveHTML());
                     if(preg_match('/<body>(.*)?<\/body>/s',$navstr,$match)){
                         $navstr = $match[1];
                     }
                     $this->ncx_navmap =  $navstr;

                  }

            }
        }
      /*if($start){
            $end = microtime(true);
            echo $end.'<br>'.$start.'<br>';
            echo ($end-$start);
            die;
        }*/
        return TRUE;
    }


    /**
	 * Process external references from a HTML to the book. The chapter itself is not stored.
	 * the HTML is scanned for &lt;link..., &lt;style..., and &lt;img tags.
	 * Embedded CSS styles and links will also be processed.
	 * Script tags are not processed, as scripting should be avoided in e-books.
	 *
	 * EPub keeps track of added files, and duplicate files referenced across multiple
	 *  chapters, are only added once.
	 *
	 * If the $doc is a string, it is assumed to be the content of an HTML file,
	 *  else is it assumes to be a DOMDocument.
	 *
	 * Basedir is the root dir the HTML is supposed to "live" in, used to resolve
	 *  relative references such as <code>&lt;img src="../images/image.png"/&gt;</code>
	 *
	 * $externalReferences determins how the function will handle external references.
	 *
	 * @param mixed  $doc (referenced)
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? Default is EPub::EXTERNAL_REF_ADD.
	 * @param String $baseDir Default is "", meaning it is pointing to the document root.
	 * @param String $htmlDir The path to the parent HTML file's directory from the root of the archive.
	 *
	 * @return Bool  FALSE if uncuccessful (book is finalized or $externalReferences == EXTERNAL_REF_IGNORE).
	 */
	protected function processChapterExternalReferences(&$doc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "") {
		if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
			return FALSE;
		}
		//$this->indexChapter++;
      /*  if($this->indexChapter == 100){
            $start = microtime(true);
        }*/
		$backPath = preg_replace('#[^/]+/#i', "../", $htmlDir);
        if(!preg_match('/charset=utf-8/',$doc)){
            $doc=preg_replace_callback('/<title>/',function($m){
                return '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">'.$m[0];
            },$doc);
        }
		$isDocAString = is_string($doc);
		$xmlDoc = NULL;
		
		if ($isDocAString) {
			$xmlDoc = new DOMDocument();
			@$xmlDoc->loadHTML($doc);
		} else {
			$xmlDoc = $doc;
		}
        $this-> processChapterMedia($xmlDoc, $externalReferences, $baseDir, $htmlDir, $backPath);
        $this->processChapterLinks($xmlDoc, $externalReferences, $baseDir, $htmlDir, $backPath);
		$this->processChapterStyles($xmlDoc, $externalReferences, $baseDir, $htmlDir);
		$this->processChapterInnerStyles($xmlDoc, $externalReferences, $baseDir, $htmlDir);
		$this->processChapterImages($xmlDoc, $externalReferences, $baseDir, $htmlDir, $backPath);
		if ($isDocAString) {
			$html = $xmlDoc->saveXML();
			$head = $xmlDoc->getElementsByTagName("head");
			$body = $xmlDoc->getElementsByTagName("body");
			$xml = new DOMDocument('1.0', "utf-8");
			$xml->lookupPrefix("http://www.w3.org/1999/xhtml");
			$xml->preserveWhiteSpace = FALSE;
			$xml->formatOutput = TRUE;

			$xml2Doc = new DOMDocument('1.0', "utf-8");
			$xml2Doc->lookupPrefix("http://www.w3.org/1999/xhtml");
			$xml2Doc->loadXML("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n</html>\n");
			$html = $xml2Doc->getElementsByTagName("html")->item(0);
			
			$html->appendChild($xml2Doc->importNode($head->item(0), TRUE));
			$html->appendChild($xml2Doc->importNode($body->item(0), TRUE));

			// force pretty printing and correct formatting, should not be needed, but it is.
			$xml->loadXML($xml2Doc->saveXML());
			$doc = $xml->saveXML();
		}
		/*if($start){
		    $end = microtime(true);
            echo ($end- $start);
            die;
        }*/
		return TRUE;
	}

	/**
	 * Process images referenced from an CSS file to the book.
	 *
	 * $externalReferences determins how the function will handle external references.
	 *
	 * @param String $cssFile (referenced)
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? Default is EPub::EXTERNAL_REF_ADD.
	 * @param String $baseDir Default is "", meaning it is pointing to the document root.
	 * @param String $cssDir The of the CSS file's directory from the root of the archive.
	 *
	 * @return Bool  FALSE if uncuccessful (book is finalized or $externalReferences == EXTERNAL_REF_IGNORE).
	 */
	protected function processCSSExternalReferences(&$cssFile, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $cssDir = "") {
		if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
			return FALSE;
		}
		$backPath = preg_replace('#[^/]+/#i', "../", $cssDir);
		preg_match_all('#url\s*\([\'\"\s]*(.+?)[\'\"\s]*\)#im', $cssFile, $imgs, PREG_SET_ORDER);
		$itemCount = count($imgs);
        if(!$itemCount) return false;
		for ($idx = 0; $idx < $itemCount; $idx++) {
			$img = $imgs[$idx];
			if ($externalReferences === EPub::EXTERNAL_REF_REMOVE_IMAGES || $externalReferences === EPub::EXTERNAL_REF_REPLACE_IMAGES) {
				$cssFile = str_replace($img[0], "", $cssFile);
			} else {
				$source = $img[1];
                $pathData = pathinfo($source);
                $internalSrc = $pathData['basename'];
				$internalPath = '';//111
				$isSourceExternal = FALSE;
                $backPath = ($this->cmk == 1) ? '../Images/':'';
                /*if(!array_key_exists($internalPath, $this->fileList)){*/
                    if ($this->resolveImage($source, $internalPath, $internalSrc, $isSourceExternal, $baseDir, $cssDir, $backPath)) {
                        $url = $backPath . $internalPath;
                        $cssFile = str_replace($img[0], "url('" . $url . "')", $cssFile);

                    }else if ($isSourceExternal) {
                        $cssFile = str_replace($img[0], "", $cssFile); // External image is missing
                    } // else do nothing, if the image is local, and missing, assume it's been generated.
                /*}else{
                    $url =  $internalPath;
                    $cssFile = str_replace($img[0], "url('" . $url . "')", $cssFile);

                }*/

			}
		}
		return TRUE;
	}

	/**
	 * Process style tags in a DOMDocument. Styles will be passed as CSS files and reinserted into the document.
	 *
	 * @param DOMDocument $xmlDoc (referenced)
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? Default is EPub::EXTERNAL_REF_ADD.
	 * @param String $baseDir  Default is "", meaning it is pointing to the document root.
	 * @param String $htmlDir  The path to the parent HTML file's directory from the root of the archive.
	 *
	 * @return Bool  FALSE if uncuccessful (book is finalized or $externalReferences == EXTERNAL_REF_IGNORE).
	 */
	protected function processChapterStyles(&$xmlDoc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "") {
		if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
			return FALSE;
		}
		// process inlined CSS styles in style tags.
		$styles = $xmlDoc->getElementsByTagName("style");
		$styleCount = $styles->length;
		for ($styleIdx = 0; $styleIdx < $styleCount; $styleIdx++) {
			$style = $styles->item($styleIdx);
			$styleData = $style->nodeValue;

			$styleData = preg_replace('#[/\*\s]*\<\!\[CDATA\[[\s\*/]*#im', "", $styleData);
			$styleData = preg_replace('#[/\*\s]*\]\]\>[\s\*/]*#im', "", $styleData);
			$this->processCSSExternalReferences($styleData, $externalReferences, $baseDir, $htmlDir);
			$style->nodeValue  = "\n" . trim($styleData) . "\n";
		}
		return TRUE;
	}
    protected function processChapterInnerStyles(&$xmlDoc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "") {
        if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
            return FALSE;
        }
        $tags = $xmlDoc->getElementsByTagName("html");
        $tag = $tags->item(0);
        $outerHTML = $tag->ownerDocument->saveHTML($tag);
        $outerHTML = preg_replace('#[/\*\s]*\<\!\[CDATA\[[\s\*/]*#im', "", $outerHTML);
        $outerHTML = preg_replace('#[/\*\s]*\]\]\>[\s\*/]*#im', "", $outerHTML);
        if(preg_match_all('#style=[\'\"](.+?)[\'\"]#is',$outerHTML,$match)){
            foreach($match[1] as $v){
                if(preg_match('#url\s*\([\'\"\s]*(.+?)[\'\"\s]*\)#',$v, $img)){
                    if ($externalReferences === EPub::EXTERNAL_REF_REMOVE_IMAGES || $externalReferences === EPub::EXTERNAL_REF_REPLACE_IMAGES) {
                        $search[] = $img[0];
                        $replace[] = '';
                    }else{
                        $source = $img[1];
                        $pathData = pathinfo($source);
                        $internalSrc = $pathData['basename'];
                        $internalPath = "";
                        $isSourceExternal = FALSE;
                        if($this->cmk == 1){
                            $backPath = '../Images/';
                        }else{
                            $backPath = '';
                        }
                        if(array_key_exists($source,$this->fileList)){
                            $search[] = $img[0];
                            $replace[] = "url('" . $this->fileList[$source] . "')";
                        }else{
                            if ($this->resolveImage($source, $internalPath, $internalSrc, $isSourceExternal, $baseDir, $htmlDir, $backPath)) {
                                $search[] = $img[0];
                                $replace[] = "url('" . $backPath . $internalPath . "')";
                                $this->fileList[$source] = $backPath . $internalPath;
                            } else if ($isSourceExternal) {
                                $search[] = $img[0];
                                $replace[] = '';
                            }
                        }
                    }
                }
            }
            $outerHTML = str_replace($search,$replace,$outerHTML);
            $outerHTML = str_replace('&#13;','',$outerHTML);
            $xmlDoc->loadHTML($outerHTML);
            $xmlDoc->saveHTML();
        }
        return TRUE;
    }
	/**
	 * Process link tags in a DOMDocument. Linked files will be loaded into the archive, and the link src will be rewritten to point to that location.
	 * Link types text/css will be passed as CSS files.
	 *
	 * @param DOMDocument $xmlDoc (referenced)
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? Default is EPub::EXTERNAL_REF_ADD.
	 * @param String $baseDir  Default is "", meaning it is pointing to the document root.
	 * @param String $htmlDir  The path to the parent HTML file's directory from the root of the archive.
	 * @param String $backPath The path to get back to the root of the archive from $htmlDir.
	 *
	 * @return Bool  FALSE if uncuccessful (book is finalized or $externalReferences == EXTERNAL_REF_IGNORE).
	 */
	protected function processChapterLinks(&$xmlDoc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "", $backPath = "") {
		if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
			return FALSE;
		}
		$links = $xmlDoc->getElementsByTagName("link");
		$linkCount = $links->length;
        if(!$linkCount) return false;
		for ($linkIdx = 0; $linkIdx < $linkCount; $linkIdx++) {
			$link = $links->item($linkIdx);
			$source = $link->attributes->getNamedItem("href")->nodeValue;
			$sourceData = NULL;
			$internalSrc = md5($source).'.css';
            if(!array_key_exists($source,$this->fileList)){
               if (preg_match('#^(http|ftp)s?://#i', $source) == 1) {
                    @$sourceData = file_get_contents($source);
                }elseif(preg_match('#^\/dzz\//#i', $source) == 1) {
					@$sourceData = file_get_contents(str_replace('//','/',getglobal('siteurl').$source)); 
				}elseif(preg_match('#^dzz\//#i', $source) == 1) { 
					@$sourceData = file_get_contents(getglobal('siteurl').$source);
				}else {
                    @$sourceData = file_get_contents($source);
                }
				
                if (!empty($sourceData)) {
                    $mime = $link->attributes->getNamedItem("type")->nodeValue;
                    if (empty($mime)) {
                        $mime = "text/plain";
                    }
					if ($mime == "text/css") {
                        if(!array_key_exists($internalSrc, $this->fileList)){
                            $this->processCSSExternalReferences($sourceData, $externalReferences, $baseDir, $htmlDir);
                            $this->addCSSFile($internalSrc, $internalSrc, $sourceData,EPub::EXTERNAL_REF_IGNORE, $baseDir);
                            $this->csstmpfile = $source;
                        }
                        if($this->cmk == 1) $path = '../Styles/'.$internalSrc;
                        else $path = $internalSrc;
                        $link->setAttribute("href",$path);
                    } else {
                        $this->addFile($internalSrc, $internalSrc, $sourceData, $mime);
                    }
                    $this->fileList[$source] = $path;
                }
            }else {
                $link->setAttribute("href",$this->fileList[$source]);
            }
            unset($sourceData);
        }
		return TRUE;
	}
	protected  function processChapterMedia(&$xmlDoc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "", $backPath = ""){
        if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
            return FALSE;
        }
        $medias = $xmlDoc->getElementsByTagName("video");
        $mediaCount = $medias->length;
        if($mediaCount < 0) return false;
        for ($mediaIdx = 0; $mediaIdx < $mediaCount; $mediaIdx++) {
            $media = $medias->item($mediaIdx);
            $sourceImage = $media->attributes->getNamedItem("poster")->nodeValue;
            $source = $media->getElementsByTagName('source')->item(0);
            $sourceUrl = trim($source->attributes->getNamedItem("src")->nodeValue);
			/*if (preg_match('#^(http|ftp)s?://#i', $sourceUrl) == 1) {
            	//$sourceData = file_get_contents($sourceUrl);
			} else*/
			if (strpos($sourceUrl, '/') === 0 ) {
				$sourceUrl=getglobal('siteurl') . substr($sourceUrl,1);
			} elseif (strpos($sourceUrl, 'index.php') === 0 ) {
				$sourceUrl = getglobal('siteurl') . $sourceUrl;
			} /*else {
				//$sourceData = file_get_contents($sourceUrl);
			}*/
            
			
            $pathData = pathinfo($sourceUrl);
            $ext = strtolower(substr(strrchr($sourceUrl, '.'), 1, 10));
            $internalSrc = md5($sourceUrl).'.'.$ext;
            if (!array_key_exists($sourceUrl, $this->fileList)) {
                    $href = ($this->cmk == 1) ? "../Media/" . $internalSrc :$internalSrc;
                    if($this->saveMediaFile($sourceUrl,$internalSrc)) $source->setAttribute('src',$href);

                    $pathData = pathinfo($sourceImage);
                    $internalSrc = $pathData['basename'];
                    $isSourceExternal = FALSE;
                    if($this->cmk == 1){
                        $backPath = '../Images/';
                    }else{
                        $backPath = '';
                    }
                    $internalPath = '';
                    if($this->resolveImage($sourceImage, $internalPath, $internalSrc, $isSourceExternal, $baseDir, $htmlDir, $backPath)){
                        $replace =  $backPath . $internalPath;
                    }else{
                        $replace = '';
                    }
                    $media->setAttribute('poster',$replace);
            }
            //} // else do nothing, if the link is local, and missing, assume it's been generated.
        }
        return ;
    }
   protected function saveMediaFile($fileName, $internalSrc,$fileData = NULL, $mimetype = NULL) {
	   //??????????????????????????????????????????
        if ($this->isFinalized || array_key_exists($fileName, $this->fileList) || strpos($fileName,getglobal('siteurl'))===false) {
            return FALSE;
        }
        $mediaArray = array('mp3'=>'audio/mpeg', 'mp4'=>'video/mp4','ogg'=>'audio/ogg', 'ogv'=>'video/ogg','webm'=>'video/webm');
		
        if ($fileData == NULL) { // assume $fileName is the valig file path.
           
            $ext = strtolower(substr(strrchr($fileName, '.'), 1, 10));
           if(!array_key_exists($ext,$mediaArray)){
               return false;
           }else{
               $mediaType = $mediaArray[$ext];
           }
		   $fileData = file_get_contents($fileName);
        }
        $href = ($this->cmk == 1) ?'Media/'.$internalSrc:$internalSrc;
        $newFileName = ($this->cmk == 1) ? "OEBPS/Media/" . $internalSrc:$internalSrc;
        $this->zip->addFile($fileData, $newFileName);
        $this->fileList[$fileName] = $newFileName;
        $this->opf_manifest = "\t\t<item id=\"".$internalSrc."\" href=\"".$href."\" media-type=\"".$mediaType."\" />\n" . $this->opf_manifest;
        return TRUE;
    }
	/**
	 * Process img tags in a DOMDocument.
	 * $externalReferences will determine what will happen to these images, and the img src will be rewritten accordingly.
	 *
	 * @param DOMDocument $xmlDoc (referenced)
	 * @param int    $externalReferences How to handle external references, EPub::EXTERNAL_REF_IGNORE, EPub::EXTERNAL_REF_ADD or EPub::EXTERNAL_REF_REMOVE_IMAGES? Default is EPub::EXTERNAL_REF_ADD.
	 * @param String $baseDir  Default is "", meaning it is pointing to the document root.
	 * @param String $htmlDir  The path to the parent HTML file's directory from the root of the archive.
	 * @param String $backPath The path to get back to the root of the archive from $htmlDir.
	 *
	 * @return Bool  FALSE if uncuccessful (book is finalized or $externalReferences == EXTERNAL_REF_IGNORE).
	 */
	protected function processChapterImages(&$xmlDoc, $externalReferences = EPub::EXTERNAL_REF_ADD, $baseDir = "", $htmlDir = "", $backPath = "") {
		if ($this->isFinalized || $externalReferences === EPub::EXTERNAL_REF_IGNORE) {
			return FALSE;
		}
		
		// process img tags.
		$postProcDomElememts = array();
		$images = $xmlDoc->getElementsByTagName("img");
		$itemCount = $images->length;
        if(!$itemCount) return false;
		for ($idx = 0; $idx < $itemCount; $idx++) {
			$img = $images->item($idx);
			if ($externalReferences === EPub::EXTERNAL_REF_REMOVE_IMAGES) {
				$postProcDomElememts[] = $img;
			} else if ($externalReferences === EPub::EXTERNAL_REF_REPLACE_IMAGES) {
				$postProcDomElememts[] = array($img, $this->createDomFragment($xmlDoc, "<em>[image]</em>"));
			} else {
				$source = $img->attributes->getNamedItem("src")->nodeValue;
				$pathData = pathinfo($source);
				$internalSrc = $pathData['basename'];
				$internalPath = "";
				$isSourceExternal = FALSE;
                $backPath = ($this->cmk == 1) ? '../Images/':'';
				if ($this->resolveImage($source, $internalPath, $internalSrc, $isSourceExternal, $baseDir, $htmlDir, $backPath)) {
                    $src = $backPath . $internalPath;
					$img->setAttribute("src", $src);
				} else if ($isSourceExternal) {
					$postProcDomElememts[] = $img; // External image is missing
				} // else do nothing, if the image is local, and missing, assume it's been generated.
			}
		}

		foreach ($postProcDomElememts as $target) {
			if (is_array($target)) {
				$target[0]->parentNode->replaceChild($target[1], $target[0]);
			} else {
				$target->parentNode->removeChild($target);
			}
		}
		return TRUE;
	}

	/**
	 * Resolve an image src and determine it's target location and add it to the book.
	 *
	 * @param String $source Image Source link.
	 * @param String $internalPath (referenced) Return value, will be set to the target path and name in the book.
	 * @param String $internalSrc (referenced) Return value, will be set to the target name in the book.
	 * @param String $isSourceExternal (referenced) Return value, will be set to TRUE if the image originated from a full URL.
	 * @param String $baseDir  Default is "", meaning it is pointing to the document root.
	 * @param String $htmlDir  The path to the parent HTML file's directory from the root of the archive.
	 * @param String $backPath The path to get back to the root of the archive from $htmlDir.
	 */
	protected function resolveImage($source, &$internalPath, &$internalSrc, &$isSourceExternal, $baseDir = "", $htmlDir = "", $backPath = "") {
		if ($this->isFinalized) {
			return FALSE;
		}
		if(array_key_exists($source,$this->fileList)){
		    $internalPath = $this->fileList[$source];
             return true;
        }
		$imageData  = FALSE;
        $source = trim(str_replace('&amp;','&',$source));
        if (preg_match('#^(http|ftp)s?://#i', $source) == 1) {
            $imageData = $this->getImage($source);
        } elseif (strpos($source, '/') === 0 ) {
            $imageData = $this->getImage(getglobal('siteurl') . substr($source,1));
		} elseif (strpos($source, 'index.php') === 0 ) {
            $imageData = $this->getImage(getglobal('siteurl') . $source);
        } else {
            $imageData = $this->getImage($source);
        }
		if ($imageData !== FALSE) {
            $exts = explode('/',$imageData['mime']);
            $ext = $exts[1];
            $internalPath = Zip::getRelativePath(($internalPath?$internalPath.'/':''). md5($internalSrc).'.'.$ext);
		    if (!array_key_exists($internalPath, $this->fileList)) {
		        $imgname = md5($internalPath).'.'.strtolower(substr(strrchr($internalPath, '.'), 1, 10));
				$this->addImageFile($internalPath, $imgname, $imageData['image'], $imageData['mime'],$imageData['width'],$imageData['height']);
				$this->fileList[$source] = $internalPath;
			}
		}else{
		    $internalPath = '';
		    $source = $this->docRoot.'/dzz/images/b.gif';
           // @$sourceData = file_get_contents($source);
            $imageData = $this->getImage($source);
            $exts = explode('/',$imageData['mime']);
            $ext = $exts[1];
            $internalPath = Zip::getRelativePath(($internalPath?$internalPath.'/':''). md5($internalSrc).'.'.$ext);
            if (!array_key_exists($internalPath, $this->fileList)) {
                $imgname = md5($internalPath).'.'.strtolower(substr(strrchr($internalPath, '.'), 1, 10));
                $this->addImageFile($internalPath, $imgname, $imageData['image'], $imageData['mime'],$imageData['width'],$imageData['height']);
                $this->fileList[$source] = $internalPath;
            }
        }
		return true;
	}
    function addImageFile($fileName, $fileId,  $fileData, $mimetype,$width,$height){
        if ($this->isFinalized || array_key_exists($fileName, $this->fileList)) {
            return FALSE;
        }
        $newFileName = preg_replace('#\\\#i', "/", $fileName);
        $newFileName = preg_replace('#^[/\.]+#i', "", $newFileName);
        if($this->cmk == 1){
            $href = 'Images/'.$newFileName;
            $newFileName = 'OEBPS/Images/'.$newFileName;
        }else{
            $href = $newFileName;
            $newFileName = $newFileName;
        }
        $this->zip->addFile($fileData, $newFileName);
        $this->fileList[$fileName] = $newFileName;
        $this->opf_manifest .= "\t\t<item width= \"".$width."\" id=\"" . $fileId . "\" href=\"" . $href . "\" media-type=\"" . $mimetype . "\" height= \"".$height."\" />\n";
        return TRUE;
    }
	/**
	 * Add a cover image to the book.
	 *
	 * The styling and structure of the generated XHTML is heavily inspired by the XHTML generated by Calibre.
	 *
	 * @param String $fileName Filename to use for the image, must be unique for the book.
	 * @param String $imageData Binary image data
	 * @param String $mimetype Image mimetype, such as "image/jpeg" or "image/png".
	 * @return bool $success
	 */
	function setCoverImage($fileName, $imageData = NULL, $mimetype = NULL) {
		if ($this->isFinalized || $this->isCoverImageSet || array_key_exists("CoverPage.html", $this->fileList)) {
			return FALSE;
		}
		if ($imageData == NULL) { // assume $fileName is the valig file path.
			$image = $this->getImage($fileName);
			$imageData = $image['image'];
			$mimetype = $image['mime'];
		}
		$path = pathinfo($fileName);
		$imgPath = ($this->cmk == 1) ? "OEBPS/Images/" . $path["basename"] :$path["basename"];
        $paths = ($this->cmk == 1) ?"../Images/" . $path["basename"]:$path["basename"];
        $href = ($this->cmk == 1) ?'Images/'.$path["basename"]:$path["basename"];

		$coverPage = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"zh-CN\">\n\t<head>\n\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/><meta name=\"calibre:cover\" content=\"true\"/>\n\t\t<title>Cover Image</title>\n\t\t<style type=\"text/css\" title=\"css\">\n\t\t\t@page, body, div, img {\n\t\t\t\tpadding: 0pt;\n\t\t\t\tmargin:0pt;\n\t\t\t}\n\t\t\tbody {\n\t\t\t\ttext-align: center;\n\t\t\t}\n\t\t</style>\n\t</head>\n\t<body>\n\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" version=\"1.1\" width=\"100%\" height=\"100%\" viewBox=\"0 0 1200 1600\" preserveAspectRatio=\"none\"><image width=\"". $image['width'] ."\" height=\"". $image['height'] ."\" xlink:href=\"". $paths . "\"/></svg>\n\t</body>\n</html>\n";
        if($this->cmk == 1){
            $htmlurl =  "OEBPS/Text/CoverPage.xhtml";
            $url = "Text/CoverPage.xhtml";
        }else{
            $htmlurl = 'titlepage.xhtml';
            $url = 'titlepage.xhtml';
        }
		$this->zip->addFile($coverPage,$htmlurl);
		$this->zip->addFile($imageData, $imgPath);
		$this->fileList["CoverPage.html"] = $htmlurl;
		$this->fileList[$imgPath] = $fileName;
		$this->opf_manifest = "\t\t<item width=\"". $image['width'] ."\" id=\"cover\" href=\"" .$href. "\" media-type=\"" . $mimetype . "\"  height=\"". $image['height'] ."\"/>\n" . $this->opf_manifest;
		$this->opf_manifest = "\t\t<item id=\"coverPage\" href=\"".$url."\" media-type=\"application/xhtml+xml\" />\n" . $this->opf_manifest;
		$this->opf_spine = "\t\t<itemref idref=\"coverPage\" linear=\"yes\" properties=\"duokan-page-fullscreen\" />\n" . $this->opf_spine;
		$this->opf_guide .= "\t\t<reference href=\"".$url."\" type=\"cover\" title=\"coverPage\"/>\n";
		/*$this->ncx_navmap = "\n\t\t<navPoint id=\"\" playOrder=\"0\">\n\t\t\t<navLabel><text>??????</text></navLabel>\n\t\t\t<content src=\"Text/CoverPage.html\" />\n\t\t</navPoint>\n" . $this->ncx_navmap;*/

		$this->isCoverImageSet = TRUE;
		return TRUE;
	}
	/**
	 * Get Book Chapter count.
	 *
	 * @access public
	 * @return number of chapters
	 */
	function getChapterCount() {
		return $this->chapterCount;
	}

	/**
	 * Book title, mandatory.
	 *
	 * Used for the dc:title metadata parameter in the OPF file as well as the DocTitle attribute in the NCX file.
	 *
	 * @param string $title
	 * @access public
	 * @return bool $success
	 */
	function setTitle($title) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->title = $title;
		return TRUE;
	}

	/**
	 * Get Book title.
	 *
	 * @access public
	 * @return $title
	 */
	function getTitle() {
		return $this->title;
	}

	/**
	 * Book language, mandatory
	 *
	 * Use the RFC3066 Language codes, such as "en", "da", "fr" etc.
	 * Defaults to "en".
	 *
	 * Used for the dc:language metadata parameter in the OPF file.
	 *
	 * @param string $language
	 * @access public
	 * @return bool $success
	 */
	function setLanguage($language) {
		if ($this->isFinalized || mb_strlen($language) != 2) {
			return FALSE;
		}
		$this->language = $language;
		return TRUE;
	}

	/**
	 * Get Book language.
	 *
	 * @access public
	 * @return $language
	 */
	function getLanguage() {
		return $this->language;
	}

	/**
	 * Unique book identifier, mandatory.
	 * Use the URI, or ISBN if available.
	 * 
	 * An unambiguous reference to the resource within a given context.
	 * 
	 * Recommended best practice is to identify the resource by means of a
	 *  string conforming to a formal identification system. 
	 *
	 * Used for the dc:identifier metadata parameter in the OPF file, as well
	 *  as dtb:uid in the NCX file.
	 *
	 * Identifier type should only be:
	 *  EPub::IDENTIFIER_URI
	 *  EPub::IDENTIFIER_ISBN
	 *  EPub::IDENTIFIER_UUID
	 *
	 * @param string $identifier
	 * @param string $identifierType
	 * @access public
	 * @return bool $success
	 */
	function setIdentifier($identifier, $identifierType) {
		if ($this->isFinalized || ($identifierType !== EPub::IDENTIFIER_URI && $identifierType !== EPub::IDENTIFIER_ISBN && $identifierType !== EPub::IDENTIFIER_UUID)) {
			return FALSE;
		}
		$this->identifier = $identifier;
		$this->identifierType = $identifierType;
		return TRUE;
	}

	/**
	 * Get Book identifier.
	 *
	 * @access public
	 * @return $identifier
	 */
	function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Get Book identifierType.
	 *
	 * @access public
	 * @return $identifierType
	 */
	function getIdentifierType() {
		return $this->identifierType;
	}

	/**
	 * Book description, optional.
	 *
	 * An account of the resource.
	 * 
	 * Description may include but is not limited to: an abstract, a table of
	 *  contents, a graphical representation, or a free-text account of the
	 *  resource.
	 * 
	 * Used for the dc:source metadata parameter in the OPF file
	 *
	 * @param string $description
	 * @access public
	 * @return bool $success
	 */
	function setDescription($description) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->description = $description;
		return TRUE;
	}

	/**
	 * Get Book description.
	 *
	 * @access public
	 * @return $description
	 */
	function getDescription() {
		return $this->description;
	}

	/**
	 * Book author or creator, optional.
	 * The $authorSortKey is basically how the name is to be sorted, usually
	 *  it's "Lastname, First names" where the $author is the straight
	 *  "Firstnames Lastname"
	 *
	 * An entity primarily responsible for making the resource.
	 * 
	 * Examples of a Creator include a person, an organization, or a service.
	 *  Typically, the name of a Creator should be used to indicate the entity.
	 *
	 * Used for the dc:creator metadata parameter in the OPF file and the
	 *  docAuthor attribure in the NCX file.
	 * The sort key is used for the opf:file-as attribute in dc:creator.
	 *
	 * @param string $author
	 * @param string $authorSortKey
	 * @access public
	 * @return bool $success
	 */
	function setAuthor($author, $authorSortKey) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->author = $author;
		$this->authorSortKey = $authorSortKey;
		return TRUE;
	}

	/**
	 * Get Book author.
	 *
	 * @access public
	 * @return $author
	 */
	function getAuthor() {
		return $this->author;
	}

	/**
	 * Publisher Information, optional.
	 * 
	 * An entity responsible for making the resource available.
	 * 
	 * Examples of a Publisher include a person, an organization, or a service.
	 *  Typically, the name of a Publisher should be used to indicate the entity.
	 *
	 * Used for the dc:publisher and dc:relation metadata parameters in the OPF file.
	 *
	 * @param string $publisherName
	 * @param string $publisherURL
	 * @access public
	 * @return bool $success
	 */
	function setPublisher($publisherName, $publisherURL) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->publisherName = $publisherName;
		$this->publisherURL = $publisherURL;
		return TRUE;
	}

	/**
	 * Get Book publisherName.
	 *
	 * @access public
	 * @return $publisherName
	 */
	function getPublisherName() {
		return $this->publisherName;
	}

	/**
	 * Get Book publisherURL.
	 *
	 * @access public
	 * @return $publisherURL
	 */
	function getPublisherURL() {
		return $this->publisherURL;
	}

	/**
	 * Release date, optional. If left blank, the time of the finalization will
	 *  be used.
	 *  
	 * A point or period of time associated with an event in the lifecycle of
	 *  the resource.
	 *
	 * Date may be used to express temporal information at any level of
	 *  granularity.  Recommended best practice is to use an encoding scheme,
	 *  such as the W3CDTF profile of ISO 8601 [W3CDTF].
	 *
	 * Used for the dc:date metadata parameter in the OPF file
	 *
	 * @param long $timestamp
	 * @access public
	 * @return bool $success
	 */
	function setDate($timestamp) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->date = $timestamp;
		return TRUE;
	}

	/**
	 * Get Book date.
	 *
	 * @access public
	 * @return $date
	 */
	function getDate() {
		return $this->date;
	}

	/**
	 * Book (copy)rights, optional.
	 * 
	 * Information about rights held in and over the resource.
	 * 
	 * Typically, rights information includes a statement about various
	 *  property rights associated with the resource, including intellectual
	 *  property rights.
	 *
	 * Used for the dc:rights metadata parameter in the OPF file
	 *
	 * @param string $rightsText
	 * @access public
	 * @return bool $success
	 */
	function setRights($rightsText) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->rights = $rightsText;
		return TRUE;
	}

	/**
	 * Get Book rights.
	 *
	 * @access public
	 * @return $rights
	 */
	function getRights() {
		return $this->rights;
	}

	/**
	 * Set book Subject.
	 * 
	 * The topic of the resource.
	 * 
	 * Typically, the subject will be represented using keywords, key phrases,
	 *  or classification codes. Recommended best practice is to use a
	 *  controlled vocabulary. To describe the spatial or temporal topic of the
	 *  resource, use the Coverage element.
	 * 
	 * @param String $subject
	 */
	function setSubject($subject) {
		if ($this->isFinalized) {
			return;
		}
		$this->subject = $subject;
	}

	/**
	 * Get the book subject.
	 * 
	 * @return String The Subject.
	 */
	function getSubject() {
		return $this->subject;
	}

	/**
	 * Book source URL, optional.
	 * 
	 * A related resource from which the described resource is derived.
	 * 
	 * The described resource may be derived from the related resource in whole
	 *  or in part. Recommended best practice is to identify the related
	 *  resource by means of a string conforming to a formal identification system.
	 *
	 * Used for the dc:source metadata parameter in the OPF file
	 *
	 * @param string $sourceURL
	 * @access public
	 * @return bool $success
	 */
	function setSourceURL($sourceURL) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->sourceURL = $sourceURL;
		return TRUE;
	}

	/**
	 * Get Book sourceURL.
	 *
	 * @access public
	 * @return $sourceURL
	 */
	function getSourceURL() {
		return $this->sourceURL;
	}
	
	/**
	 * Coverage, optional.
	 * 
	 * The spatial or temporal topic of the resource, the spatial applicability
	 *  of the resource, or the jurisdiction under which the resource is relevant.
	 * 
	 * Spatial topic and spatial applicability may be a named place or a location
	 *  specified by its geographic coordinates. Temporal topic may be a named
	 *  period, date, or date range. A jurisdiction may be a named administrative
	 *  entity or a geographic place to which the resource applies. Recommended
	 *  best practice is to use a controlled vocabulary such as the Thesaurus of
	 *  Geographic Names [TGN]. Where appropriate, named places or time periods
	 *  can be used in preference to numeric identifiers such as sets of
	 *  coordinates or date ranges.
	 *
	 * Used for the dc:coverage metadata parameter in the OPF file
	 *
	 * @param string $coverage
	 * @access public
	 * @return bool $success
	 */
	function setCoverage($coverage) {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->coverage = $coverage;
		return TRUE;
	}

	/**
	 * Get Book coverage.
	 *
	 * @access public
	 * @return $coverage
	 */
	function getCoverage() {
		return $this->coverage;
	}

	/**
	 * Set book Relation.
	 * 
	 * A related resource.
	 * 
	 * Recommended best practice is to identify the related resource by means
	 *  of a string conforming to a formal identification system. 
	 * 
	 * @param String $relation
	 */
	function setRelation($relation) {
		if ($this->isFinalized) {
			return;
		}
		$this->relation = $relation;
	}

	/**
	 * Get the book relation.
	 * 
	 * @return String The relation.
	 */
	function getRelation() {
		return $this->relation;
	}
	
	/**
	 * Set book Generator.
	 * 
	 * The generator is a meta tag added to the ncx file, it is not visible
	 *  from within the book, but is a kind of electronic watermark.
	 * 
	 * @param String $generator
	 */
	function setGenerator($generator) {
		if ($this->isFinalized) {
			return;
		}
		$this->generator = $generator;
	}

	/**
	 * Get the book relation.
	 * 
	 * @return String The generator identity string.
	 */
	function getGenerator() {
		return $this->generator;
	}

	/**
	 * Set ePub date formate to the short yyyy-mm-dd form, for compliance with
	 *  a bug in EpubCheck, prior to its version 1.1.
	 * 
	 * The latest version of ePubCheck can be obtained here:
	 *  http://code.google.com/p/epubcheck/
	 *
	 * @access public
	 * @return bool $success
	 */
	function setShortDateFormat() {
		if ($this->isFinalized) {
			return FALSE;
		}
		$this->dateformat = $this->dateformatShort;
		return TRUE;
	}

	/**
	 * @Deprecated
	 */
	function setIgnoreEmptyBuffer($ignoreEmptyBuffer = TRUE) {
		return TRUE;
	}

	/**
	 * Get Book status.
	 *
	 * @access public
	 * @return boolean
	 */
	function isFinalized() {
		return $this->isFinalized;
	}

	/**
	 * Check for mandatory parameters and finalize the e-book.
	 * Once finalized, the book is locked for further additions.
	 *
	 * @return bool $success
	 */
	function finalize($filename) {

	    @unlink($this->csstmpfile);
		if ($this->isFinalized || $this->chapterCount == 0 || empty($this->title) || empty($this->language)) {
			return FALSE;
		}
		if (empty($this->identifier) || empty($this->identifierType)) {
			$this->setIdentifier($this->createUUID(4), EPub::IDENTIFIER_UUID);
		}

		if ($this->date == 0) {
			$this->date = time();
		}
		if(empty($this->sourceURL)) {
			$this->sourceURL = $this->getCurrentPageURL();
		}

		if(empty($this->publisherURL)) {
			$this->sourceURL = $this->getCurrentServerURL();
		}
		// Generate OPF data:
		$this->opf = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<package xmlns=\"http://www.idpf.org/2007/opf\" unique-identifier=\"BookId\" version=\"2.0\">\n\t<metadata xmlns:dc=\"http://purl.org/dc/elements/1.1/\"\n\t\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n\t\txmlns:opf=\"http://www.idpf.org/2007/opf\"\n\t\txmlns:dcterms=\"http://purl.org/dc/terms/\">\n\t\t<dc:title>"
		. $this->title . "</dc:title>\n\t\t<dc:language>"
		. $this->language . "</dc:language>\n\t\t<dc:identifier id=\"BookId\" opf:scheme=\""
		. $this->identifierType . "\">"
		. $this->identifier . "</dc:identifier>\n";

		if (!empty($this->description)) {
			$this->opf .= "\t\t<dc:description>" . $this->description . "</dc:description>\n";
		}
			
		if (!empty($this->publisherName)) {
			$this->opf .= "\t\t<dc:publisher>" . $this->publisherName . "</dc:publisher>\n";
		}
			
		if (!empty($this->publisherURL)) {
			$this->opf .= "\t\t<dc:relation>" . $this->publisherURL . "</dc:relation>\n";
		}

		if (!empty($this->author)) {
			$this->opf .= "\t\t<dc:creator";
			if (!empty($this->authorSortKey)) {
				$this->opf .= " opf:file-as=\"" . $this->authorSortKey . "\"";
			}
			$this->opf .= " opf:role=\"aut\">" . $this->author . "</dc:creator>\n";
		}

		$this->opf .= "\t\t<dc:date>" . gmdate($this->dateformat, $this->date) . "</dc:date>\n";
			
		if (!empty($this->rights)) {
			$this->opf .= "\t\t<dc:rights>" . $this->rights . "</dc:rights>\n";
		}

		if(!empty($this->subject)) {
			$this->opf .=  "\t\t<dc:subject>" . $this->subject . "</dc:subject>\n";
		}
		
		if(!empty($this->coverage)) {
			$this->opf .=  "\t\t<dc:coverage>" . $this->coverage . "</dc:coverage>\n";
		}
		
		if (!empty($this->sourceURL)) {
			$this->opf .=  "\t\t<dc:source>" . $this->sourceURL . "</dc:source>\n";
		}

		if(!empty($this->relation)) {
			$this->opf .=  "\t\t<dc:relation>" . $this->relation . "</dc:relation>\n";
		}
		
		if ($this->isCoverImageSet) {
			$this->opf .= "\t\t<meta name=\"cover\" content=\"cover\" />\n";
		}
		
		if ($this->EPubMark) {
			$this->ncx .= "\t\t<meta name=\"generator\" content=\"EPub (" . self::VERSION . ") by A. Grandt, http://www.phpclasses.org/package/6115\" />\n";
		}
		if (!empty($this->generator)) {
			$this->ncx .= "\t\t<meta name=\"generator\" content=\"" . $this->generator . "\" />\n";
		}
		
		$this->opf .= "\t</metadata>\n\n\t<manifest>\n" . $this->opf_manifest . "\t</manifest>\n\n\t<spine toc=\"ncx\">\n" . $this->opf_spine . "\t</spine>\n";

		if (!empty($this->opf_guide)) {
			$this->opf .= "\n\t<guide>\n" . $this->opf_guide . "\t</guide>\n";
		}

		$this->opf .= "</package>\n";

		$this->ncx = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ncx xmlns=\"http://www.daisy.org/z3986/2005/ncx/\" version=\"2005-1\" xml:lang=\"zh-CN\">\n\t<head>\n"
		. "\t\t<meta name=\"dtb:uid\" content=\"" . $this->identifier . "\" />\n\t\t<meta name=\"dtb:depth\" content=\"2\" />\n\t\t<meta name=\"dtb:totalPageCount\" content=\"0\" />\n\t\t<meta name=\"dtb:maxPageNumber\" content=\"0\" />\n";
		
		if ($this->EPubMark) {
			$this->ncx .= "\t\t<meta name=\"dtb:generator\" content=\"EPub (" . self::VERSION . ") by A. Grandt, http://www.phpclasses.org/package/6115\" />\n";
		}
		if (!empty($this->generator)) {
			$this->ncx .= "\t\t<meta name=\"dtb:generator\" content=\"" . $this->generator . "\" />\n";
		}
		$this->ncx .= "\t</head>\n\n\t<docTitle>\n\t\t<text>"
		. $this->title . "</text>\n\t</docTitle>\n\n";

		if (!empty($this->author)) {
			$this->ncx .= "\t<docAuthor>\n\t\t<text>" . $this->author . "</text>\n\t</docAuthor>\n\n";
		}
        $nav_str = '';
        if(preg_match_all('/<body>(.*?)<\/body>/s',$this->ncx_navmap,$match)){
            foreach($match[1] as $v){
                $nav_str .= $v;
            }
        }else{
            $nav_str .= preg_replace('/<meta.*?<\/title>/','',$this->ncx_navmap);
        }
        $nav_str = str_replace('navpoint','navPoint',$nav_str);
        $nav_str = str_replace('navlabel','navLabel',$nav_str);
        $nav_str = str_replace('playorder','playOrder',$nav_str);
        $this->ncx_navmap = $nav_str;
		$this->ncx .= "\t<navMap>\n" . $this->ncx_navmap . "\t</navMap>\n</ncx>\n";
        if($this->cmk == 1){
            $opfurl = "OEBPS/content.opf";
            $ncxurl = "OEBPS/toc.ncx";
        }else{
            $opfurl = "content.opf";
            $ncxurl = "toc.ncx";
        }
		$this->zip->addFile($this->opf, $opfurl);
		$this->zip->addFile($this->ncx, $ncxurl);
		$this->opf = "";
		$this->ncx = "";
		$this->isFinalized = TRUE;
        $data  = $this->zip->getZipData();
        file_put_contents($filename,$data);
        $this->__destruct();
		return TRUE;
	}

	/**
	 * Return the finalized book.
	 *
	 * @return String with the book in binary form.
	 */
	function getBook() {
		if(!$this->isFinalized) {
			$this->finalize();
		}

		return $this->zip->getZipData();
	}

	/**
	 * Return the finalized book.
	 *
	 * @return String
	 */
	function getBookSize() {
		if(!$this->isFinalized) {
			$this->finalize();
		}

		return $this->zip->getArchiveSize();
	}
	
	/**
	 * Save the finalized book to disc.
	 * 
	 * @return bool $success
	 */
	function saveBook($filename) {
		if(!$this->isFinalized) {
			$this->finalize();
		}
		
		return $this->zip->setZipFile($filename);
	}

	/**
	 * Send the book as a zip download
	 *
	 * Sending will fail if the output buffer is in use. You can override this limit by
	 *  calling setIgnoreEmptyBuffer(TRUE), though the function will still fail if that
	 *  buffer is not empty.
	 *
	 * @param String $fileName The name of the book without the .epub at the end.
	 * @return bool $success
	 */
	function sendBook($fileName) {
		if(!$this->isFinalized) {
			$this->finalize();
		}

		if (stripos(strrev($fileName), "bupe.") !== 0) {
			$fileName .= ".epub";
		}
		return $this->zip->getZipData();
		//return $this->zip->sendZip($fileName, "application/epub+zip");
	}

	/**
	 * Generates an UUID.
	 *
	 * Default version (4) will generate a random UUID, version 3 will URL based UUID.
	 *
	 * Added for convinience
	 *
	 * @param      $version UUID version to retrieve, See lib.uuid.manual.html for details.
	 * @return     string   The formatted uuid
	 */
	function createUUID($version = 4, $url = NULL) {
		include_once("lib.uuid.php");
		return UUID::mint($version,$url,UUID::nsURL);
	}

	/**
	 * Get the url of the current page.
	 * Example use: Default Source URL
	 *
	 * $return Page URL as a string.
	 */
	function getCurrentPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://" . $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= ":" . $_SERVER["SERVER_PORT"];
		}
		$pageURL .= $_SERVER["REQUEST_URI"];
		return $pageURL;
	}

	/**
	 * Get the url of the server.
	 * Example use: Default Publisher URL
	 *
	 * $return Server URL as a string.
	 */
	function getCurrentServerURL() {
		$serverURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {
			$serverURL .= "s";
		}
		$serverURL .= "://" . $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_PORT"] != "80") {
			$serverURL .= ":" . $_SERVER["SERVER_PORT"];
		}
		return $serverURL . '/';
	}

	/**
	 * Get an image from a file or url, return it resized if the image exceeds the $maxImageWidth or $maxImageHeight directives.
	 *
	 * The return value is an array.
	 * ['width'] is the width of the image.
	 * ['height'] is the height of the image.
	 * ['mime'] is the mime type of the image. Resized images are always in jpeg format.
	 * ['image'] is the image data.
	 *
	 * @param String $source path or url to file.
	 * $return array
	 */
	function getImage($source) {
		list($width, $height, $type, $attr) = getimagesize($source);
		$mime = image_type_to_mime_type($type);

		if ($width == 0 || $height == 0) {
			return FALSE;
		}

		@$image = file_get_contents($source);
		if ($image === FALSE) {
			return FALSE;
		}
		$ratio = 1;

		if ($this->isGdInstalled) {
			if ($width > $this->maxImageWidth) {
				$ratio = $this->maxImageWidth/$width;
			}
			if ($height*$ratio > $this->maxImageHeight) {
				$ratio = $this->maxImageHeight/$height;
			}
			if ($ratio < 1) {
				$image_o = imagecreatefromstring($image);
				$image_p = imagecreatetruecolor($width*$ratio, $height*$ratio);
				imagecopyresampled($image_p, $image_o, 0, 0, 0, 0, ($width*$ratio), ($height*$ratio), $width, $height);
				ob_start();
				imagejpeg($image_p,NULL,80);
				$image = ob_get_contents();
				ob_end_clean();
				imagedestroy($image_o);
				imagedestroy($image_p);
				$mime = "image/jpeg";
			}
		}
		$rv = array();
		$rv['width'] = $width*$ratio;
		$rv['height'] = $height*$ratio;
		$rv['mime'] = $mime;
		$rv['image'] = $image;

		return $rv;
	}

	/**
	 * Helper function to create a DOM fragment with given markup.
	 *
	 * @author Adam Schmalhofer
	 *
	 * @param DOMDocument $dom
	 * @param String $markup
	 * @return DOMNode fragment in a node.
	 */
	protected function createDomFragment($dom, $markup) {
		$node = $dom->createDocumentFragment();
		$node->appendXML($markup);
		return $node;
	}

	/**
	 * Retrieve an array of file names currently added to the book.
	 * $key is the filename used in the book
	 * $value is the original filename, will be the same as $key for most entries
	 *
	 * @return array file list
	 */
	function getFileList() {
		return $this->fileList;
	}

	/**
	 * Clean up a path
	 * If the path starts with a "/", it is deemed absolute and any /../ in the beginning is stripped off.
	 * The returned path will not end in a "/".
	 *
	 * @param String $relPath The path to clean up
	 * @return String the clean path
	 * @deprecated Redundant, please use Zip::getRelativePath($relPath) instead.
	 */
	function relPath($relPath) {
		return Zip::getRelativePath($relPath);
	}

	/**
	 * Set default chapter target size.
	 * Default is 250000 bytes, and minimum is 10240 bytes.
	 *
	 * @param $size
	 * @return void
	 */
	function setSplitSize($size) {
		$this->splitDefaultSize = (int)$size;
		if ($size < 10240) {
			$this->splitDefaultSize = 10240; // Making the file smaller than 10k is not a good idea.
		}
	}

	/**
	 * Get the chapter target size.
	 *
	 * @return $size
	 */
	function getSplitSize() {
		return $this->splitDefaultSize;
	}
}