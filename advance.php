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
</style>
</head>
<body>

<table border=1  style="table-layout: fixed; width: 100%;">
<tr><th>Category</th><th>Advancement</th>
<?php

$MCDIR = "/home/mc/minecraft";


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

echo "</tr>";

$categories = dir("$MCDIR/cache/1.17.1/data/minecraft/advancements");
while (false !== ($category = $categories->read())) {
	$advancements = dir("$MCDIR/cache/1.17.1/data/minecraft/advancements/$category");
	if($category!="recipes") while (false !== ($advancement = $advancements->read())) {
		if(is_file("$MCDIR/cache/1.17.1/data/minecraft/advancements/$category/$advancement") && $advancement!='root.json' && $advancement != 'all_effects.json'&& $advancement != 'arbalistic.json' ) 
		{
		
			$adv_data = json_decode(file_get_contents("$MCDIR/cache/1.17.1/data/minecraft/advancements/$category/$advancement"),true);;
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
	  			  		if (!array_key_exists($requirement[0],$user["advancements"][$adv]["criteria"]))
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


echo "<tr><td>Statistics</td><td>Hours played</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . number_format($user["stats"]["stats"]["minecraft:custom"]["minecraft:play_time"] /20/60/60,0,',','.') . "</td>";
}
echo "</tr>";

echo "<tr><td>Statistics</td><td>Diamond Pickaxes broken</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:broken"]["minecraft:diamond_pickaxe"] . "</td>";
}
echo "</tr>";

echo "<tr><td>Statistics</td><td>Netherite Pickaxes broken</td>";
foreach($users as &$user)
{
	echo "<td align=right>" . $user["stats"]["stats"]["minecraft:broken"]["minecraft:netherite_pickaxe"] . "</td>";
}
echo "</tr>";



?>
</table>
</body>
</html>
