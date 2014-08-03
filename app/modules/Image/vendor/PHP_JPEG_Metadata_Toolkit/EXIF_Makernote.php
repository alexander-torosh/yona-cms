<?php

/******************************************************************************
*
* Filename:     EXIF_Makernote.php
*
* Description:  Provides functions for reading EXIF Makernote Information
*               The actual functions for reading each manufacturers makernote
*               are provided in the Makernotes directory.
*
* Author:       Evan Hunter
*
* Date:         23/7/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.00 -> 1.11 : changed makernotes directory definition to allow
*                              the toolkit to be portable across directories
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
*
* License:      This file is part of the PHP JPEG Metadata Toolkit.
*
*               The PHP JPEG Metadata Toolkit is free software; you can
*               redistribute it and/or modify it under the terms of the
*               GNU General Public License as published by the Free Software
*               Foundation; either version 2 of the License, or (at your
*               option) any later version.
*
*               The PHP JPEG Metadata Toolkit is distributed in the hope
*               that it will be useful, but WITHOUT ANY WARRANTY; without
*               even the implied warranty of MERCHANTABILITY or FITNESS
*               FOR A PARTICULAR PURPOSE.  See the GNU General Public License
*               for more details.
*
*               You should have received a copy of the GNU General Public
*               License along with the PHP JPEG Metadata Toolkit; if not,
*               write to the Free Software Foundation, Inc., 59 Temple
*               Place, Suite 330, Boston, MA  02111-1307  USA
*
*               If you require a different license for commercial or other
*               purposes, please contact the author: evan@ozhiker.com
*
******************************************************************************/


// Create the Makernote Parser and Interpreter Function Array

$GLOBALS['Makernote_Function_Array'] = array(   "Read_Makernote_Tag" => array( ),
                                                "get_Makernote_Text_Value" => array( ),
                                                "Interpret_Makernote_to_HTML" => array( ) );


// Include the Main TIFF and EXIF Tags array

include_once 'EXIF.php';



/******************************************************************************
*
* Include the Makernote Scripts
*
******************************************************************************/

// Set the Makernotes Directory

$dir = dirname(__FILE__) . "/Makernotes/";      // Change: as of version 1.11 - to allow directory portability

// Open the directory
$dir_hnd = @opendir ( $dir );

// Cycle through each of the files in the Makernotes directory

while ( ( $file = readdir( $dir_hnd ) ) !== false )
{
        // Check if the current item is a file
        if ( is_file ( $dir . $file ) )
        {
                // Item is a file, break it into it's parts
                $path_parts = pathinfo( $dir . $file );

                // Check if the extension is php
                if ( $path_parts["extension"] == "php" )
                {
                        // This is a php script - include it
                        include_once ($dir . $file) ;
                }
        }
}
// close the directory
closedir( $dir_hnd );










/******************************************************************************
*
* Function:     Read_Makernote_Tag
*
* Description:  Attempts to decodes the Makernote tag supplied, returning the
*               new tag with the decoded information attached.
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               EXIF_Array - the entire EXIF array containing the
*                            makernote, as returned from get_EXIF_JPEG, in
*                            case more information is required for decoding
*               filehnd - an open file handle for the file containing the
*                         makernote - does not have to be positioned at the
*                         start of the makernote
*
*
* Returns:      Makernote_Tag - the Makernote_Tag from the parameters, but
*                               modified to contain the decoded information
*
******************************************************************************/

function Read_Makernote_Tag( $Makernote_Tag, $EXIF_Array, $filehnd )
{

        // Check if the Makernote is present but empty - this sometimes happens
        if ( ( strlen( $Makernote_Tag['Data'] ) === 0 ) ||
             ( $Makernote_Tag['Data'] === str_repeat ( "\x00", strlen( $Makernote_Tag['Data'] )) ) )
        {
                // Modify the makernote to display that it is empty
                $Makernote_Tag['Decoded Data'] = "Empty";
                $Makernote_Tag['Makernote Type'] = "Empty";
                $Makernote_Tag['Makernote Tags'] = "Empty";
                $Makernote_Tag['Decoded'] = TRUE;

                // Return the new makernote
                return $Makernote_Tag;
        }

        // Check if the Make Field exists in the TIFF IFD
        if ( array_key_exists ( 271, $EXIF_Array[0] ) )
        {
                // A Make tag exists in IFD0, collapse multiple strings (if any), and save result
                $Make_Field = implode ( "\n", $EXIF_Array[0][271]['Data']);
        }
        else
        {
                // No Make field found
                $Make_Field = "";
        }

        // Cycle through each of the "Read_Makernote_Tag" functions

        foreach( $GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'] as $func )
        {
                // Run the current function, and save the result
                $New_Makernote_Tag = $func( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field );

                // Check if a valid result was returned
                if ( $New_Makernote_Tag !== FALSE )
                {
                        // A valid result was returned - stop cycling
                        break;
                }
        }

        // Check if a valid result was returned
        if ( $New_Makernote_Tag === false )
        {
                // A valid result was NOT returned - construct a makernote tag representing this
                $New_Makernote_Tag = $Makernote_Tag;
                $New_Makernote_Tag['Decoded'] = FALSE;
                $New_Makernote_Tag['Makernote Type'] = "Unknown Makernote";
        }

        // Return the new makernote tag
        return $New_Makernote_Tag;

}

/******************************************************************************
* End of Function:     Read_Makernote_Tag
******************************************************************************/









/******************************************************************************
*
* Function:     get_Makernote_Text_Value
*
* Description:  Attempts to provide a text value for any makernote tag marked
*               as type special. Returns false no handler could be found to
*               process the tag
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from Read_Makernote_Tag
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If no handler could be found to process this tag, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Makernote_Text_Value( $Tag, $Tag_Definitions_Name )
{

        // Cycle through each of the "get_Makernote_Text_Value" functions

        foreach( $GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'] as $func )
        {
                // Run the current function, and save the result
                $Text_Val = $func( $Tag, $Tag_Definitions_Name );

                // Check if a valid result was returned
                if ( $Text_Val !== FALSE )
                {
                        // valid result - return it
                        return $Text_Val;
                }
        }

        // No Special tag handler found for this tag - return false
        return FALSE;

}


/******************************************************************************
* End of Function:     get_Makernote_Text_Value
******************************************************************************/








/******************************************************************************
*
* Function:     Interpret_Makernote_to_HTML
*
* Description:  Attempts to interpret a makernote into html.
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               filename - the name of the JPEG file being processed ( used
*                          by scripts which display embedded thumbnails)
*
*
* Returns:      output - the html representing the makernote
*
******************************************************************************/

function Interpret_Makernote_to_HTML( $Makernote_tag, $filename )
{

        // Create a string to receive the HTML
        $output_str = "";

        // Check if the makernote tag is valid
        if ( $Makernote_tag === FALSE )
        {
                // No makernote info - return
                return $output_str;
        }


        // Check if the makernote has been marked as unknown
        if ( $Makernote_tag['Makernote Type'] == "Unknown Makernote" )
        {
                // Makernote is unknown - return message
                $output_str .= "<h4 class=\"EXIF_Makernote_Small_Heading\">Unknown Makernote Coding</h4>\n";
                return $output_str;
        }
        else
        {
                // Makernote is known - add a heading to the output
                $output_str .= "<p class=\"EXIF_Makernote_Text\">Makernote Coding: " . $Makernote_tag['Makernote Type'] . "</p>\n";
        }

        // Check if this is an empty makernote
        if ( $Makernote_tag['Makernote Type'] == "Empty" )
        {
                // It is empty - don't try to interpret
                return $output_str;
        }

        // Cycle through each of the "Interpret_Makernote_to_HTML" functions

        foreach( $GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'] as $func )
        {
                // Run the current function, and save the result
                $html_text = $func( $Makernote_tag, $filename );

                // Check if a valid result was returned
                if ( $html_text !== FALSE )
                {
                        // valid result - return it
                        return $output_str . $html_text;
                }
        }

        // No Interpreter function handled the makernote - return a message

        $output_str .= "<h4 class=\"EXIF_Makernote_Small_Heading\">Could not Decode Makernote, it may be corrupted or empty</h4>\n";

        return $output_str;


}

/******************************************************************************
* End of Function:     Interpret_Makernote_to_HTML
******************************************************************************/



?>