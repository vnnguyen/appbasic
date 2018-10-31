
<?php

    /*

    * The php.ini must be in the com.allow_dcom set to TRUE

    */



    function php_Word($wordname,$htmlname,$content)

    {

        //Get the link address

        $url = $_SERVER['HTTP_HOST'];

        $url = '';

        $url = $url.$_SERVER['PHP_SELF'];

        $url = dirname($url)."/";

        //The establishment of a pointer to a new COM component index

        $word = new COM("word.application") or die("Unable to instanciate Word");


        //Display the current version of Word you are using

        echo "Loading Word, v. {$word->Version}";



        //The visibility is set to 0 (false), if you want to make it open at the front, the use of 1 (true)

        $word->Visible = 1;

        //---------------------------------Read the content of the Word operation START-----------------------------------------

        //Open a word document

        $word->Documents->Open($url.$wordname);



        //Convert filename.doc to HTML format, and save the file as HTML

        $word->Documents[1]->SaveAs(dirname(__FILE__)."/".$htmlname,8);



        //Access to the contents of the HTM file and output to the page (not the text style loss)

        $content = file_get_contents($url.$htmlname);

        echo $content;



        //Gets a word document content and output to the page (the original style of text is missing)

        $content= $word->ActiveDocument->content->Text;

        echo $content;



        //Close the connection with the COM component

        $word->Documents->close(true);

        $word->Quit();

        $word = null;

        unset($word);

        //---------------------------------The new Word document START-------------------------------------- operation

        //Create an empty word file

        $word->Documents->Add();



        //Write the content to the new word

        $word->Selection->TypeText("$content");



        //Save the new word document

        $word->Documents[1]->SaveAs(dirname(__FILE__)."/".$wordname);



        //Close the connection with the COM component

        $word->Quit();

    }

    php_Word("D:/wamp/www/demo.amica-travel.com/upload/ideas/tours/output/devis-ims/test.docx","D:/wamp/www/demo.amica-travel.com/ajaxphpdocx/test.html","Write the contents of the word");

?>