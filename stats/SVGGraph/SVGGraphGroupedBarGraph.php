<?php
/**
 * Copyright (C) 2011-2013 Graham Breach
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * For more information, please contact <graham@goat1000.com>
 */

require_once 'SVGGraphMultiGraph.php';
require_once 'SVGGraphBarGraph.php';

class GroupedBarGraph extends BarGraph {

  protected $multi_graph;

  protected function Draw()
  {
    $body = $this->Grid() . $this->Guidelines(SVGG_GUIDELINE_BELOW);

    $chunk_count = count($this->multi_graph);
    $gap_count = $chunk_count - 1;
    $bar_width = ($this->bar_space >= $this->bar_unit_width ? '1' : 
      $this->bar_unit_width - $this->bar_space);
    $chunk_gap = $gap_count > 0 ? $this->group_space : 0;
    if($gap_count > 0 && $chunk_gap * $gap_count > $bar_width - $chunk_count)
      $chunk_gap = ($bar_width - $chunk_count) / $gap_count;
    $chunk_width = ($bar_width - ($chunk_gap * ($chunk_count - 1)))
      / $chunk_count;
    $chunk_unit_width = $chunk_width + $chunk_gap;
    $bar_style = array();
    $bar = array('width' => $chunk_width);

    $b_start = $this->pad_left + ($this->bar_space / 2);
    $bspace = $this->bar_space / 2;
    $bnum = 0;
    $ccount = count($this->colours);
    $bars_shown = array_fill(0, $chunk_count, 0);

    foreach($this->multi_graph as $itemlist) {
      $k = $itemlist[0]->key;
      $bar_pos = $this->GridPosition($k, $bnum);
      if(!is_null($bar_pos)) {
        for($j = 0; $j < $chunk_count; ++$j) {
          $bar['x'] = $bspace + $bar_pos + ($j * $chunk_unit_width);
          $item = $itemlist[$j];
          $this->SetStroke($bar_style, $item, $j);
          $bar_style['fill'] = $this->GetColour($item, $j % $ccount);

          if(!is_null($item->value)) {
            $this->Bar($item->value, $bar);

            if($bar['height'] > 0) {
              ++$bars_shown[$j];

              if($this->show_tooltips)
                $this->SetTooltip($bar, $item, $item->value, null,
                  !$this->compat_events && $this->show_bar_labels);
              $rect = $this->Element('rect', $bar, $bar_style);
              if($this->show_bar_labels)
                $rect .= $this->BarLabel($item, $bar);
              $body .= $this->GetLink($item, $k, $rect);
              unset($bar['id']); // clear for next generated value
            }
          }
          $this->bar_styles[$j] = $bar_style;
        }
      }
      ++$bnum;
    }
    if(!$this->legend_show_empty) {
      foreach($bars_shown as $j => $bar) {
        if(!$bar)
          $this->bar_styles[$j] = NULL;
      }
    }

    $body .= $this->Guidelines(SVGG_GUIDELINE_ABOVE) . $this->Axes();
    return $body;
  }

  /**
   * construct multigraph
   */
  public function Values($values)
  {
    parent::Values($values);
    if(!$this->values->error)
      $this->multi_graph = new MultiGraph($this->values, $this->force_assoc,
        $this->require_integer_keys);
  }

  /**
   * Find the full length
   */
  protected function GetHorizontalCount()
  {
    return $this->multi_graph->ItemsCount(-1);
  }

  /**
   * Returns the maximum value
   */
  protected function GetMaxValue()
  {
    return $this->multi_graph->GetMaxValue();
  }

  /**
   * Returns the minimum value
   */
  protected function GetMinValue()
  {
    return $this->multi_graph->GetMinValue();
  }

  /**
   * Returns the key from the MultiGraph
   */
  protected function GetKey($index)
  {
    return $this->multi_graph->GetKey($index);
  }

  /**
   * Returns the maximum key from the MultiGraph
   */
  protected function GetMaxKey()
  {
    return $this->multi_graph->GetMaxKey();
  }

  /**
   * Returns the minimum key from the MultiGraph
   */
  protected function GetMinKey()
  {
    return $this->multi_graph->GetMinKey();
  }

}

