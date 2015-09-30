<?php

class Pager {

  private $page;
  private $limit;
  private $offset;
  private $table;
  private $term;
  private $places;
  private $rows;
  private $link;
  private $maxlinks;
  private $first;
  private $last;
  private $paginator;

  public function __construct($link, $first = null, $last = null, $maxlinks = null) {
    $this->link = (string) $link;
    $this->first = ((string) $first ? $first : "Primeira");
    $this->last = ((string) $last ? $last : "Ultima");
    $this->maxlinks = ((int) $maxlinks ? $maxlinks : 5);
  }

  public function exePager($page, $limit) {
    $this->page = ((int) $page ? $page : 1);
    $this->limit = (int) $limit;
    $this->offset = ($this->page * $this->limit) - $this->limit;
  }

  public function returnPage() {
    if ($this->page > 1):
      $nPage = $this->page - 1;
      header("Location : {$this->link}{$nPage}");
    endif;
  }

  public function getLimit() {
    return $this->limit;
  }

  public function getOffset() {
    return $this->offset;
  }

  public function exePaginator($table, $term = null, $parseString = null) {
    $this->table = (string) $table;
    $this->term = (string) $term;
    $this->places = (string) $parseString;
    $this->getSyntax();
  }

  public function getPaginator() {
    return $this->paginator;
  }

  private function getSyntax() {
    $read = new Read;
    $read->exeRead($this->table, $this->term, $this->places);
    $this->rows = $read->getRowCount();

    if ($this->rows > $this->limit):
      $pages = ceil($this->rows / $this->limit);
      $maxlinks = $this->maxlinks;

      $this->paginator = "<nav><ul class=\"pagination\">";

      $this->paginator .= "<li><a title=\"Primeira Página\" href=\"{$this->link}1\"><span aria-hidden=\"true\"></span><span class=\"sr_only\">{$this->first}</span></a></li>";

      for ($iPag = $this->page - $maxlinks; $iPag <= $this->page - 1; $iPag++):
        if ($iPag >= 1):
          $this->paginator .= "<li><a title=\"Página {$iPag}\" href=\"{$this->link}{$iPag}\">{$iPag}</a></li>";
        endif;
      endfor;

      $this->paginator .= "<li class=\"active\"><a href=\"#\">$this->page<span class=\"sr-only\">(current)</span></a></li>";

      for ($dPag = $this->page + 1; $dPag <= $this->page + $maxlinks; $dPag++):
        if ($dPag <= $pages):
          $this->paginator .= "<li><a title=\"Página {$dPag}\" href=\"{$this->link}{$dPag}\">{$dPag}</a></li>";
        endif;
      endfor;

      $this->paginator .= "<li><a title=\"Ultima Página\" href=\"{$this->link}{$pages}\"><span aria-hidden=\"true\"></span><span class=\"sr_only\">{$this->last}</span></a></li>";

      $this->paginator .= "</nav>";
    endif;
  }

}
