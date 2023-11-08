<?php
include 'Unit5_database.php';

function createPuzzleTable($conn,$allPuzzles) {
    $html = '<table class = "puzzleTable">
              <thead>
                <tr>
                  <th>Puzzle</th>
                  <th>Image</th>
                  <th>Pieces</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Inactive?</th>
                </tr>
              </thead>
              <tbody>';
  
    // Add rows to the table HTML
    foreach ($allPuzzles as $puzzle) {
      $inactiveDisplay = $puzzle['inactive'] == 1 ? 'Yes' : 'No';
      $html .= "<tr data-id='".htmlspecialchars($puzzle['id'])."'>
                  <td>".htmlspecialchars($puzzle['product_name'])."</td>
                  <td>".htmlspecialchars($puzzle['image_name'])."</td>
                  <td>".htmlspecialchars($puzzle['puzzle_pieces'])."</td>
                  <td>".htmlspecialchars($puzzle['in_stock'])."</td>
                  <td>".htmlspecialchars($puzzle['price'])."</td>
                  <td>{$inactiveDisplay}</td>
                </tr>";
    }
  
    // Close the table HTML
    $html .= '</tbody></table>';
    return $html;
}
 
?>
