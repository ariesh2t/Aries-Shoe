<?php echo "<p class='float-start' style='line-height: 40px'>Trang " . $current_page . "/" . $total_page . "</p>"; ?>
<div class="pagination justify-content-center mb-5">
    <?php
    $url = $_SERVER['QUERY_STRING'];
    $pos = strpos($url, '&page');
    if ($pos) {
        $url = substr($url, 0, $pos);
    }

    if ($total_page > 1) {
        $prev = $current_page - 1; $next = $current_page + 1;

        echo "<a href='index.php?" . $url . "&page=1" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-left'></i></a>";

        if ($prev >= 1) {
            echo "<a href='index.php?" . $url . "&page=$prev" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></a>";
            echo "<a href='index.php?" . $url . "&page=$prev" . "' class='btn btn-info me-2 rounded-circle'>$prev</a>";
        } else {
            echo "<span class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></span>";
        }

        echo "<a href='index.php?" . $url . "&page=$current_page" . "' class='btn btn-warning me-2 rounded-circle'>$current_page</a>";

        if ($next <= $total_page) {
            echo "<a href='index.php?" . $url . "&page=$next" . "' class='btn btn-info me-2 rounded-circle'>$next</a>";
            echo "<a href='index.php?" . $url . "&page=$next" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></a>";
        }else {
            echo "<span class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></span>";
        }

        echo "<a href='index.php?" . $url . "&page=$total_page" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-right'></i></a>";
    }
    ?>
</div>