<?php
$pdf = pdf_new();

#Pass the object at the first position in all of the PHP pdf functions when required for that page. To open the file, code we use the pdf_open_file function.

pdf_open_file($pdf, "C:\apache\htdocs\tmp\bennyboy.pdf");

#This should create a blank new pdf file size 0kb. The new file has no properties, so let’s assign some. You’ll need to use the pdf_set_info function for this.

pdf_set_info($pdf, "Author", "Ben Shepherd");
pdf_set_info($pdf, "Title", "Creating a pdf");
pdf_set_info($pdf, "Creator", "Ben Shepherd");
pdf_set_info($pdf, "Subject", "Creating a pdf");

#Now we have all the particulars taken care of let’s do some pdf manipulation. Top begin we need to use the pdf_begin_page function. The parameters, apart from the first which is always $pdf, are measures in of the width and height respectively. A4 is 595 x 842, Letter is 612 x 792 and Legal is 612 x 1008.

pdf_begin_page($pdf, 595, 842);
#veraltet, ersetzt durch 
#pdf_begin_page_ext();
#PDF_begin_document($pdf, 595, 842);

#Now it is time to assign a text font for the information to be displayed. Simply use the pdf_findfont and pdf_setfont to do this. I choose the Arial font type with size of 14.

$arial = pdf_findfont($pdf, "Arial", "host", 1);
pdf_setfont($pdf, $arial, 14);

#Now we have set the font type, it is time to use it. To display text in the pdf file you must use the pdf_show_xy function. The x-values (i.e. the third parameter), start from the left hand side of the page and move to the right. The y-values start from the bottom of the page and work towards the top.

#So, it is said that, when you work with the pdf_show_xy function the page starts at the bottom left hand corner of the page. So if we wish to type some text 50 units from the left of the page and 400 units from the bottom of the page you would type the following.

pdf_show_xy($pdf, "<Type your info here>",50, 400);

#But you may not want just text on a page. If you are creating a pdf document for a client you may wish to display a logo. There are functions like pdf_open_gif and pdf_open_jpeg that will open up images and assign them to an object to use in the document.

$png_image = pdf_open_png($pdf, "C:\apache\cat_br\mapa\datos\reference.png");

#To put the object onto the pdf file you use the pdf_place_image function with the parameter being pdf file, image file, x-value, y-value and scale repectively.

pdf_place_image($pdf, $png_image, 200, 300, 1.0);

#You must close the image to put it out of use.

pdf_close_image($pdf, $png_image);
 
#Let’s end the pdf manipulation process by using the pdf_end_page and the pdf_close functions.

pdf_end_page($pdf);
pdf_close($pdf);

#Now to view your pdf file, simply create a link to open the pdf in a new window.

echo "<A HREF=\"C:\apache\htdocs\tmp\bennyboy.pdf\" TARGET=\"_blank\">Open pdf in a new window $user_id</A>"
?>
