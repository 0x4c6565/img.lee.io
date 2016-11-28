<?php
$files = glob("ul/*.*");
usort( $files, function( $a, $b ) { return filemtime($b) - filemtime($a); } );

for ($i=0; $i<count($files); $i++) {
    $image = $files[$i];
    print $image ."<br />";
    echo '<img src="'.$image .'" alt="Random image" />'."<br /><br />";
}
