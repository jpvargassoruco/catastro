<?php 
include "form_lista_tasas.php";

echo "<tr>\n"; 	
echo "<td> &nbsp</td>\n"; 
echo "<td valign=\"top\" height=\"40\">\n"; 

echo "<fieldset><legend>Items</legend>\n";
    echo "<table border=\"1\" width=\"100%\">\n"; 
        echo "<tr>\n";
            echo "<td align=\"left\" colspan=\"1\" class=\"bodyTextD\">Item</td>\n";
            echo "<td align=\"left\" colspan=\"1\" class=\"bodyTextD\">Nu.Punto</td>\n";
            echo "<td align=\"left\" colspan=\"1\" class=\"bodyTextD\">Perime.</td>\n";
            echo "<td align=\"left\" colspan=\"1\" class=\"bodyTextD\">Superf.</td>\n";
            echo "<td align=\"left\" colspan=\"1\" class=\"bodyTextD\">Cant.</td>\n";	
        echo "</tr>\n";
        ##################################################
        #------------------ ITEMS 1 ---------------------#
        ##################################################       
        echo "<tr>\n";  	 
            echo "<td width=\"60%\" align=\"center\" class=\"bodyText\">\n"; 	 
                echo "<select class=\"navText\" name=\"item1\" size=\"1\">\n";
                if ((!isset($_POST['item1'])) OR ($_POST['item1'] == "")) {	 
                    echo "<option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
                } else {
                    echo "<option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
                }	 
                $i = 0;
                while ($i < $no_de_subniveles) {
                    $value_temp = $id_tasa_lista[$i]; 	
                    if ($value_temp == $item1) {
                        echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
                    } else {
                        echo "<option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
                    }
                    $i++;
                } 	
                echo "</select>\n";	 
            echo "</td>\n";
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"punto1\" id=\"form_anadir2\" class=\"navText\" value=\"$punto1\"></td>\n";
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"perime1\" id=\"form_anadir2\" class=\"navText\" value=\"$perime1\"></td>\n";
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"superf1\" id=\"form_anadir2\" class=\"navText\" value=\"$superf1\"></td>\n";
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"cant1\" id=\"form_anadir2\" class=\"navText\" value=\"$cant1\"></td>\n";   	   	 	   	 	 	    
        echo "</tr>\n";

        ##################################################
        #------------------ ITEMS 2 ---------------------#
        ##################################################
        echo "<tr>\n";
            	 
            echo "<td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
                echo "<select class=\"navText\" name=\"item2\" size=\"1\">\n";
                    if ((!isset($_POST['item2'])) OR ($_POST['item2'] == "")) {	 
                        echo "<option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
                    } else {
                        echo "<option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
                    }	 
                    $i = 0;
                    while ($i < $no_de_subniveles) {
                        $value_temp = $id_tasa_lista[$i]; 	
                        if ($value_temp == $item2) {
                            echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
                        } else {
                            echo "<option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
                        }
                        $i++;
                    } 	 	
                echo "</select>\n";	 
            echo "</td>\n";	
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"punto2\" id=\"form_anadir2\" class=\"navText\" value=\"$punto2\"></td>\n";	
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"perime2\" id=\"form_anadir2\" class=\"navText\" value=\"$perime2\"></td>\n";            
            echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"superf2\" id=\"form_anadir2\" class=\"navText\" value=\"$superf2\"></td>\n";
            echo "<td align=\"center\"><input type=\"text\" name=\"cant2\" id=\"form_anadir2\" class=\"navText\" value=\"$cant2\"></td>\n";   #Col. 4	      	   	 	   	 	 	    
        echo "</tr>\n";

        ##################################################
        #------------------ ITEMS 3 ---------------------#
        ##################################################
        echo "<tr>\n";
        echo "<td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
            echo "<select class=\"navText\" name=\"item3\" size=\"1\">\n";
                if ((!isset($_POST['item3'])) OR ($_POST['item3'] == "")) {	 
                    echo "<option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
                } else {
                    echo "<option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
                }	 
                $i = 0;
                while ($i < $no_de_subniveles) {
                    $value_temp = $id_tasa_lista[$i]; 	
                    if ($value_temp == $item3) {
                        echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
                    } else {
                        echo "<option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
                    }
                    $i++;
                } 	 	
            echo "</select>\n";	 
        echo "</td>\n";	
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"punto3\" id=\"form_anadir2\" class=\"navText\" value=\"$punto3\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"perime3\" id=\"form_anadir2\" class=\"navText\" value=\"$perime3\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"superf3\" id=\"form_anadir2\" class=\"navText\" value=\"$superf3\"></td>\n";
        echo "<td align=\"center\"><input type=\"text\" name=\"cant3\" id=\"form_anadir2\" class=\"navText\" value=\"$cant3\"></td>\n";   #Col. 4	     	   	 	   	 	 	    
        echo "</tr>\n";	 	 

        ##################################################
        #------------------ ITEMS 4 ---------------------#
        ##################################################
        echo "<tr>\n";
        echo "<td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
            echo "<select class=\"navText\" name=\"item4\" size=\"1\">\n";
                if ((!isset($_POST['item4'])) OR ($_POST['item4'] == "")) {	 
                    echo "<option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
                } else {
                    echo "<option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
                }	 
                $i = 0;
                while ($i < $no_de_subniveles) {
                    $value_temp = $id_tasa_lista[$i]; 	
                    if ($value_temp == $item4) {
                        echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
                    } else {
                        echo "<option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
                    }
                    $i++;
                } 	 	
            echo "</select>\n";	 
        echo "</td>\n";	
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"punto4\" id=\"form_anadir2\" class=\"navText\" value=\"$punto4\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"perime4\" id=\"form_anadir2\" class=\"navText\" value=\"$perime4\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"superf4\" id=\"form_anadir2\" class=\"navText\" value=\"$superf4\"></td>\n";
        echo "<td align=\"center\"><input type=\"text\" name=\"cant4\" id=\"form_anadir2\" class=\"navText\" value=\"$cant4\"></td>\n";   #Col. 4	     	   	 	   	 	 	    
        echo "</tr>\n";	 	 

        ##################################################
        #------------------ ITEMS 5 ---------------------#
        ##################################################
        echo "<tr>\n";
        echo "<td align=\"center\" class=\"bodyText\">\n";   #Col. 2	 	 
            echo "<select class=\"navText\" name=\"item5\" size=\"1\">\n";
                if ((!isset($_POST['item5'])) OR ($_POST['item5'] == "")) {	 
                    echo "<option id=\"form0\" value=\"\" selected=\"selected\"> --- Seleccionar de la lista ---</option>\n";    
                } else {
                    echo "<option id=\"form0\" value=\"\"> --- Seleccionar de la lista ---</option>\n";    
                }	 
                $i = 0;
                while ($i < $no_de_subniveles) {
                    $value_temp = $id_tasa_lista[$i]; 	
                    if ($value_temp == $item5) {
                        echo "<option id=\"form0\" value=\"$value_temp\" selected=\"selected\"> &nbsp $descrip_lista[$i]</option>\n";
                    } else {
                        echo "<option id=\"form0\" value=\"$value_temp\"> &nbsp $descrip_lista[$i]</option>\n";
                    }
                    $i++;
                } 	 	
            echo "</select>\n";	 
        echo "</td>\n";	
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"punto5\" id=\"form_anadir2\" class=\"navText\" value=\"$punto5\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"perime5\" id=\"form_anadir2\" class=\"navText\" value=\"$perime5\"></td>\n";
        echo "<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"superf5\" id=\"form_anadir2\" class=\"navText\" value=\"$superf5\"></td>\n";
        echo "<td align=\"center\"><input type=\"text\" name=\"cant5\" id=\"form_anadir2\" class=\"navText\" value=\"$cant5\"></td>\n"; 
        echo "</tr>\n";
    echo "</table>\n"; 
echo "</fieldset>\n";	 	 

echo "</td>\n"; 
echo "<td> &nbsp</td>\n";  	
echo "</tr>\n";	