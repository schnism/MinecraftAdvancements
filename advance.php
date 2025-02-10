<html>
<head>
<style>
th {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  z-index: 2;
  background-color: #ffffff;
}

thead tr {
    background-color: #009879;
    color: #000000;
    text-align: left;
}

th,
td {
    padding: 12px 15px;
}

tbody tr {
    border-bottom: 1px solid #dddddd;
}

 tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

 tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

body { font-family:hack;}
</style>
<link rel='stylesheet' href='//cdn.jsdelivr.net/npm/hack-font@3.3.0/build/web/hack-subset.css'>

</head>
<body>

<table border=1  style="table-layout: fixed; width: 100%;">
<thead>
<tr><th>Category</th><th>Advancement</th>
<?php

$MCDIR = "/home/mc/minecraft";
$VER="1.21.4";

function namecmp($a, $b) {
    if ($a["name"] == $b["name"]) {
        return 0;
    }
    return ($a["name"] < $b["name"]) ? -1 : 1;
}


$users = json_decode(file_get_contents("$MCDIR/whitelist.json"),true);

uasort($users,'namecmp');

foreach($users as &$user)
{
  echo "<th>". $user["name"] . "</th>";
  $user["advancements"] = json_decode(file_get_contents("$MCDIR/world/advancements/$user[uuid].json"),true);
  $user["stats"] = json_decode(file_get_contents("$MCDIR/world/stats/$user[uuid].json"),true);
}

echo "</tr></thead><tbody>";

$categories = dir("$MCDIR/versions/$VER/data/minecraft/advancement");
while (false !== ($category = $categories->read())) {
	$advancements = dir("$MCDIR/versions/$VER/data/minecraft/advancement/$category");
	if($category!="recipes") while (false !== ($advancement = $advancements->read())) {
		if(is_file("$MCDIR/versions/$VER/data/minecraft/advancement/$category/$advancement") && $advancement!='root.json' && $advancement != 'all_effects.json'&& $advancement != 'arbalistic.json' ) 
		{
		
			$adv_data = json_decode(file_get_contents("$MCDIR/versions/$VER/data/minecraft/advancement/$category/$advancement"),true);;
			$adv = "minecraft:$category/" . str_replace('.json','',$advancement);
			echo "<tr><td>$category</td><td>" . str_replace(".json","",str_replace("_"," ",$advancement)) . "</td>";
			foreach($users as &$user)
			{
			  $achieved = array_key_exists($adv,$user["advancements"]) 
			  	&& array_key_exists("done",$user["advancements"][$adv]) 
			  	&& $user["advancements"][$adv]["done"];
			  
			  if($achieved)
	  			  echo "<td bgcolor=#00FF00></td>";
			  else
			  {			  
	  			  echo "<td align=center bgcolor=#FF0000 ";
	  			  if (count($adv_data["requirements"])>1)
	  			  {
	  			  	echo " title='";
	  			  	foreach($adv_data["requirements"] as $requirement)
	  			  	{
						if (array_key_exists($adv,$user["advancements"])) $criteria = $user["advancements"][$adv]["criteria"]; else $criteria = array(); 
	  			  		if (!array_key_exists($requirement[0],$criteria))
		  			  		echo "&#10;" . str_replace(".png","",str_replace("textures/entity/cat/","",str_replace("minecraft:","",$requirement[0])));

	  			  	}	  	
	  			  	echo "'> Missing<a";		  		
	
	  			  }
	  			  echo "></td>";
	  		  }

			}
			echo "</tr>";
		}
	}
}


echo "<tr><td colspan=2>Hours played</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . number_format($user["stats"]["stats"]["minecraft:custom"]["minecraft:play_time"] /20/60/60,0,',','.') . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Diamond Pickaxes broken</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:broken"]["minecraft:diamond_pickaxe"] . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Villagers killed</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:killed"]["minecraft:villager"] . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Netherite Pickaxes broken</td>";

foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:broken"]["minecraft:netherite_pickaxe"] . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Players killed</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:custom"]["minecraft:player_kills"] . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Died</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:custom"]["minecraft:deaths"] . "</td>";
}
echo "</tr>";

echo "<tr><td colspan=2>Totems used</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:used"]["minecraft:totem_of_undying"] . "</td>";
}
echo "</tr>";



?>
</tbody>
</table>
</body>
</html>
