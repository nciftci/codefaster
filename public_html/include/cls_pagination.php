<?php
/**
* Pagination
* This is a class for pagination implemented with the use of FastTemplate.
* This is a dependendency matter.
* Get the latest FastTemplate http://fasttemplate.grafxsoftware.com/.
* The goal is to use a MVC style.
*
* @package cls_pagination
* @version 1.0.0
* @author Elteto Zoltan
* @copyright Copyright GrafxSoftware (c) 2006
* @version $Id: cls_pagination.php,v 1.4 2006/02/22 14:53:41 zelteto Exp $
* @access public
*/

class Pagination {
    // the style of the pagination (0-5)
    protected $type = 0;
    // the number of items shown on aa page
    protected $number_items = 5;
    // the number of page grouping
    protected $number_pages = 3;
    // the url on the pagination tags
    protected $url = "";
    // the template on which the pagination is done
    protected $template = "content";
	// the dinamic zone's name
    protected $dinamic_name = "rowpag";

    // Constructor
    public function __construct()
    {
    }

    /**
    * Pagination::getType()
    *
    * @return the pagination type
    */

    public function getType()
    {
        return $this->type;
    }

    /**
    * Pagination::getNumberItems()
    *
    * @return the pagination the number of the items / page
    */

    public function getNumberItems()
    {
        return $this->number_items;
    }

    /**
    * Pagination::getUrl()
    *
    * @return the pagination url
    */
    public function getUrl()
    {
        return $this->url;
    }

    /**
    * Pagination::getNumberPages()
    *
    * @return the pagination page numbers
    */
    public function getNumberPages()
    {
        return $this->number_pages;
    }

    /**
    * Pagination::getTemplate()
    *
    * @return the pagination template
    */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Pagination::getDinamnicName()
     *
     * @return the dimanic zone's name
     **/
    public function getDinamnicName()
    {
        return $this->dinamic_name;
    }

    /**
    * Pagination::setType()
    * Set the type variable.
    *
    * @param mixed $type should be a number between 0-5
    * @return
    */

    public function setType($type)
    {
        $this->type = $type;
    }

    /**
    * Pagination::setNumberItems()
    * Set the number_items variable.
    *
    * @param mixed $number_items
    * @return
    */
    public function setNumberItems($number_items)
    {
        $this->number_items = $number_items;
    }

    /**
    * Pagination::setUrl()
    * Set the url variable.
    *
    * @param mixed $url
    * @return
    */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
    * Pagination::setNumberPages()
    * Set the number_pages variable.
    *
    * @param mixed $number_pages
    * @return
    */
    public function setNumberPages($number_pages)
    {
        $this->number_pages = $number_pages;
    }

    /**
    * Pagination::setTemplate()
    * Set the template variable.
    *
    * @param mixed $template
    * @return
    */
    public function setTemplate($template)
    {
        $this->template = $template;
    }


	/**
	 * Pagination::setDinamicName()
	 * Set the dimanic zone's name
	 *
	 * @param mixed $dinamic_name
	 * @return
	 **/
	public function setDinamicName($dinamic_name)
    {
        $this->dinamic_name = $dinamic_name;
    }


    /**
    * Pagination::typeSettings()
    * Makes the actual rules of template assignement
    * based on the type set earlier.
    * By default type=0.
    *
    * @param mixed $page This must be a FastTemplate instance.
    * @return
    */

    public function typeSettings(&$page)
    {
        switch ($this->type) {
            case 0: // showing only the prev and next tags
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                break;
            case 1: // showing only the prev and next tags with the number of items/page
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                $page->assign("PAGINATION_PREV_NUM", "1");
                $page->assign("PAGINATION_NEXT_NUM", "1");
                break;
            case 2: // showing only the prev and next + first and last tags
                $page->assign("PAGINATION_FIRST", "1");
                $page->assign("PAGINATION_LAST", "1");
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                break;
            case 3: // showing only the prev and next + first and last tags with the number of items/page
                $page->assign("PAGINATION_FIRST", "1");
                $page->assign("PAGINATION_LAST", "1");
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                $page->assign("PAGINATION_PREV_NUM", "1");
                $page->assign("PAGINATION_NEXT_NUM", "1");
                break;
            case 4: // showing only the prev and next + first and last + the total page number tags
                $page->assign("PAGINATION_TOTAL", "1");
                $page->assign("PAGINATION_FIRST", "1");
                $page->assign("PAGINATION_LAST", "1");
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                break;
            case 5: // showing only the prev and next + first and last + the total page number tags with the number of items/page
                $page->assign("PAGINATION_TOTAL", "1");
                $page->assign("PAGINATION_FIRST", "1");
                $page->assign("PAGINATION_LAST", "1");
                $page->assign("PAGINATION_PREV", "1");
                $page->assign("PAGINATION_NEXT", "1");
                $page->assign("PAGINATION_PREV_NUM", "1");
                $page->assign("PAGINATION_NEXT_NUM", "1");
                break;
        }
    }

    /**
    * make()
    * This function is making the whole job based on the
    * parameters and the earlier made settings.
    *
    * @param mixed $item_total The total number of the items.
    * @param integer $current_item The current item number.
    * @param mixed $page A FastTemplate instance.
    * @return
    */
    public function make($item_total, $current_item = 0, &$page)
    {
	 $page->assign("PAGINATION", "0");

        // calling this public function to "free" the template zones
        // with IFDEF approach
        $this->typeSettings($page);
        // total pages
        $total_pages = ceil($item_total / $this->number_items);
        // the current page
        $current_page = ceil($current_item / $this->number_items);
        // if there is more then 1 page page
        if ($total_pages > 1) {
            // if there is something to show
            $page->assign("PAGINATION", "1");
            // settings for the pagination
            $from = ($current_page - ($current_page % $this->number_pages)) * $this->number_items;
            $temp_step = $from + $this->number_items * $this->number_pages;
            $to = ($item_total < $temp_step)?$this->number_items * $total_pages:$temp_step;

			// total pages
			$page->assign("PAGINATION_TOTAL_VALUE", $total_pages);

			// if the current position is the very 1st then tags first and preview
            // should not be shown
            if ($current_item == 0) {
                $page->assign("PAGINATION_FIRST", "0");
                $page->assign("PAGINATION_PREV", "0");
            }
            // if we are at the last iteration the next and last tags
            // should not be shown
            // We also make an adjustment to the from variable in order
            // that the final iteration to be a group of $this->number_pages.
            if ($item_total <= $current_item + $this->number_items) {
                $page->assign("PAGINATION_NEXT", "0");
                $page->assign("PAGINATION_LAST", "0");
                if ($item_total > $this->number_items * $this->number_pages)
                    $from = $from - (($this->number_pages - ceil(($to - $from) / $this->number_items)) * $this->number_items);
            }
            // If type is odd then $this->number_items is revealed after the prev and next tags.
            $page->assign("PAGINATION_PREV_NUM_VALUE", $this->number_items);
            $page->assign("PAGINATION_NEXT_NUM_VALUE", $this->number_items);
            // The first and the last link tags.
            $page->assign("LINK_FIRST", $this->url . "0");
            $page->assign("LINK_LAST", $this->url . (($total_pages-1) * $this->number_items));
            // The prev and the next link tags.
            $page->assign("LINK_PREV", $this->url . ($current_item - $this->number_items));
            $page->assign("LINK_NEXT", $this->url . ($current_item + $this->number_items));
			// We prepare the dinamic part.
            $page->define_dynamic(strtolower($this->dinamic_name) , $this->template);

            for ($i = $from;$i < $to;$i = $i + $this->number_items) {
                // for the current page
				if ($i == $current_item)
                    $page->assign("PAGINATION_FEATURED", "1");
                else
                    $page->assign("PAGINATION_FEATURED", "0");

                $ind = ceil($i / $this->number_items) + 1;

                $page->assign("LINK", $this->url . $i);
                $page->assign("PAGINATION_NAME", $ind);
				$page->parse(strtoupper($this->dinamic_name),".".strtolower($this->dinamic_name));

            }
        } else // if we don't have any items to show, just switch of the pagination
            $page->assign("PAGINATION", "0");
    }
} //end Pagination

?>