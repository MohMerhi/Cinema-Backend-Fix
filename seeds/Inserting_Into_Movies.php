<?php 
require("../connection/connection.php");


$query = "INSERT INTO `movies` (`id`, `title`, `description`, `release_date`, `poster`, `trailer`, `studio`, `movie_length`) VALUES 
(NULL, 'Lilo & Stitch', 'Stitch, an extraterrestrial entity, comes to planet Earth after he escapes his prison, where he tries to impersonate a dog. Things take a turn when Lilo adopts him from an animal shelter.', '2025-05-22', 'Lilo_and_stitch.png', 'https://youtu.be/VWqJifMMgZE?si=uOZgwZDX28hxV8BN', 'Walt Disney Studios Motion Pictures', '1:48'),
(NULL, 'Heart Eyes', 'A masked maniac with glowing, red eyes returns every Valentine\'s Day to slaughter unsuspecting couples. When a cynical ad executive and her hopelessly romantic colleague become the next target, they decide to fight back and end the reign of terror.', '2025-2-7', 'Heart_Eyes.png', 'https://youtu.be/1cRzZcMlJh8?si=izm8WLTBQHpLl9Xy', 'Paramount Pictures', '01:37:00'),
 (NULL, 'The Nun', 'The plot follows a Roman Catholic priest and a nun in her novitiate as they uncover an unholy secret in 1952 Romania.', '2018-9-4', 'The_nun.png', 'https://youtu.be/pzD9zGcUNrw?si=0a2KdsQxNZmV_SVN', 'New Line Cinema', '1:36');";

$execute = $mysqli->prepare($query);
$execute->execute();


