<?php

/******************************************************************************
*
* Filename:     XMP.php
*
* Description:  Provides functions for reading and writing information to/from
*               the 'App 1' Extensible Metadata Platform (XMP) segment of JPEG
*               format files. This XMP segment is XML based and contains the
*               Resource Description Framework (RDF) data, which itself can
*               contain the Dublin Core Metadata Initiative (DCMI) information.
*
* Author:       Evan Hunter
*
* Date:         27/7/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.10
*
* Changes:      1.00 -> 1.04 : changed put_IPTC to fix a bug preventing the correct
*               insertion of a XMP block where none existed previously
*
*               1.04 -> 1.10 : changed put_XMP_text to fix some array indexes which were missing qoutes
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

include_once 'XML.php';





/******************************************************************************
*
* Function:     get_XMP_text
*
* Description:  Retrieves the Extensible Metadata Platform (XMP) information
*               from an App1 JPEG segment and returns the raw XML text as a
*               string. This includes the Resource Description Framework (RDF)
*               information and may include Dublin Core Metadata Initiative (DCMI)
*               information. Uses information supplied by the get_jpeg_header_data
*               function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*
* Returns:      xmp_data - the string of raw XML text
*               FALSE - if an APP 1 XMP segment could not be found,
*                       or if an error occured
*
******************************************************************************/

function get_XMP_text( $jpeg_header_data )
{
        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP1 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP1" ) == 0 )
                {
                        // And if it has the Adobe XMP/RDF label (http://ns.adobe.com/xap/1.0/\x00) ,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "http://ns.adobe.com/xap/1.0/\x00", 29) == 0 )
                        {
                                // Found a XMP/RDF block
                                // Return the XMP text
                                $xmp_data = substr ( $jpeg_header_data[$i]['SegData'], 29 );

                                return $xmp_data;
                        }
                }
        }
        return FALSE;
}

/******************************************************************************
* End of Function:     get_XMP_text
******************************************************************************/






/******************************************************************************
*
* Function:     put_XMP_text
*
* Description:  Adds or modifies the Extensible Metadata Platform (XMP) information
*               in an App1 JPEG segment. If a XMP segment already exists, it is
*               replaced, otherwise a new one is inserted, using the supplied data.
*               Uses information supplied by the get_jpeg_header_data function
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data
*               newXMP - a string containing the XMP text to be stored in the XMP
*                        segment. Should be constructed using the write_XMP_array_to_text
*                        function
*
* Returns:      jpeg_header_data - the JPEG header data array with the
*                                  XMP segment added.
*               FALSE - if an error occured
*
******************************************************************************/

function put_XMP_text( $jpeg_header_data, $newXMP )
{
        //Cycle through the header segments
        for( $i = 0; $i < count( $jpeg_header_data ); $i++ )
        {
                // If we find an APP1 header,
                if ( strcmp ( $jpeg_header_data[$i]['SegName'], "APP1" ) == 0 )
                {
                        // And if it has the Adobe XMP/RDF label (http://ns.adobe.com/xap/1.0/\x00) ,
                        if( strncmp ( $jpeg_header_data[$i]['SegData'], "http://ns.adobe.com/xap/1.0/\x00", 29) == 0 )
                        {
                                // Found a preexisting XMP/RDF block - Replace it with the new one and return.
                                $jpeg_header_data[$i]['SegData'] = "http://ns.adobe.com/xap/1.0/\x00" . $newXMP;
                                return $jpeg_header_data;
                        }
                }
        }

        // No pre-existing XMP/RDF found - insert a new one after any pre-existing APP0 or APP1 blocks
        // Change: changed to initialise $i properly as of revision 1.04
        $i = 0;
        // Loop until a block is found that isn't an APP0 or APP1
        while ( ( $jpeg_header_data[$i]['SegName'] == "APP0" ) || ( $jpeg_header_data[$i]['SegName'] == "APP1" ) )
        {
                $i++;
        }



        // Insert a new XMP/RDF APP1 segment at the specified point.
        // Change: changed to properly construct array element as of revision 1.04 - requires two array statements not one, requires insertion at $i, not $i - 1
        array_splice($jpeg_header_data, $i, 0, array( array(       "SegType" => 0xE1,
                                                                        "SegName" => "APP1",
                                                                        "SegDesc" => $GLOBALS[ "JPEG_Segment_Descriptions" ][ 0xE1 ],
                                                                        "SegData" => "http://ns.adobe.com/xap/1.0/\x00" . $newXMP ) ) );

        // Return the headers with the new segment inserted
        return $jpeg_header_data;
}

/******************************************************************************
* End of Function:     put_XMP_text
******************************************************************************/






/******************************************************************************
*
* Function:     read_XMP_array_from_text
*
* Description:  An alias for read_xml_array_from_text.
*               Parses a string containing XMP data (XML), and returns the resulting
*               tree structure array, which contains all the XMP (XML) information.
*               Note: White space and comments in the XMP data (XML) are ignored
*               Note: All text information contained in the tree structure
*                     is encoded as Unicode UTF-8. Hence text will appear as
*                     normal ASCII except where there is an extended character.
*
* Parameters:   xmptext - a string containing the XMP data (XML) to be parsed
*
* Returns:      output - the tree structure array containing the XMP (XML) information
*               FALSE - if an error occured
*
******************************************************************************/

function read_XMP_array_from_text( $xmptext )
{
        return read_xml_array_from_text( $xmptext );
}

/******************************************************************************
* End of Function:     read_XMP_array_from_text
******************************************************************************/





/******************************************************************************
*
* Function:     write_XMP_array_to_text
*
* Description:  Takes a tree structure array containing XMP (in the same format
*               as returned by read_XMP_array_from_text, and constructs a string
*               containing the equivalent XMP, including the XMP Packet header
*               and trailer. Produces XMP text which has correct indents, encoded
*               using UTF-8.
*               Note: All text information contained in the tree structure
*                     can be either 7-bit ASCII or encoded as Unicode UTF-8,
*                     since UTF-8 passes 7-bit ASCII text unchanged.
*
* Parameters:   xmparray - the tree structure array containing the information to
*                          be converted to XMP text
*
* Returns:      output_XMP_text - the string containing the equivalent XMP text
*
******************************************************************************/

function write_XMP_array_to_text( $xmparray )
{
        // Add the XMP packet header
        // The sequence 0xEFBBBF is the UTF-8 encoded version of the Unicode “zero
        // width non-breaking space character” (U+FEFF), which is used for detecting
        // whether UTF-16 or UTF-8 is being used.
        $output_XMP_text = "<?xpacket begin='\xef\xbb\xbf' id='W5M0MpCehiHzreSzNTczkc9d'?>\n";

        // Photoshop Seems to add this, but there doesn't appear to be
        // any information on what it means
        // TODO : XMP, Find out what the adobe-xap-filters tag means
        $output_XMP_text .= "<?adobe-xap-filters esc=\"CR\"?>\n";

        // Add the XML text
        $output_XMP_text .= write_xml_array_to_text( $xmparray, 0 );


        // The XMP standard recommends adding 2-4k of white space at the
        // end for in place editing, so we will add it to the XML now
        $output_XMP_text .= str_repeat("                                                                                                   \n", 30);

        // Add the XMP packet trailer
        $output_XMP_text .= "<?xpacket end='w'?>";

        // Return the resulting XMP text
        return $output_XMP_text;
}

/******************************************************************************
* End of Function:     write_XMP_array_to_text
******************************************************************************/






/******************************************************************************
*
* Function:     Interpret_XMP_to_HTML
*
* Description:  Generates html showing the information contained in an Extensible
*               Metadata Platform (XMP) tree structure array, as retrieved
*               with read_XMP_array_from_text
*
* Parameters:   XMP_array - a XMP tree structure array as from read_XMP_array_from_text
*
* Returns:      output - the HTML string
*
******************************************************************************/

function Interpret_XMP_to_HTML( $XMP_array )
{
        // Create a string to receive the output html
        $output ="";

        // Check if the XMP tree structure array is valid
        if ( $XMP_array !== FALSE )
        {
                // Check if there is a rdf:RDF tag at either the first or second level
                if ( ( $XMP_array[0]['tag'] ==  "x:xapmeta" ) && ( $XMP_array[0]['children'][0]['tag'] ==  "rdf:RDF" ) )
                {
                        // RDF found at second level - Save it's position
                        $RDF_Contents = &$XMP_array[0]['children'][0]['children'];
                }
                else if ( ( $XMP_array[0]['tag'] ==  "x:xmpmeta" ) && ( $XMP_array[0]['children'][0]['tag'] ==  "rdf:RDF" ) )
                {
                        // RDF found at second level - Save it's position
                        $RDF_Contents = &$XMP_array[0]['children'][0]['children'];
                }
                else if ( $XMP_array[0]['tag'] ==  "rdf:RDF" )
                {
                        // RDF found at first level - Save it's position
                        $RDF_Contents = &$XMP_array[0]['children'];
                }
                else
                {
                        // RDF section not found - abort
                        return "";
                }

                // Add heading to html output
                $output .= "<h2 class=\"XMP_Main_Heading\">Contains Extensible Metadata Platform (XMP) / Resource Description Framework (RDF) Information</h2>\n";

                // Cycle through each of the items in the RDF tree array, and process them
                foreach ($RDF_Contents as $RDF_Item)
                {
                        // Check if the item is a rdf:Description tag - these are the only ones that can be processed

                        if ( ( $RDF_Item['tag'] == "rdf:Description" ) && ( array_key_exists( 'children', $RDF_Item ) ) )
                        {
                                // Item is a rdf:Description tag.

                                // Cycle through each of the attributes for this tag, looking
                                // for a xmlns: attribute, which tells us what Namespace the
                                // sub-items will be in.
                                foreach( $RDF_Item['attributes'] as $key => $val )
                                {
                                        // Check for the xmlns: namespace attribute
                                        if ( substr( $key,0,6) == "xmlns:" )
                                        {
                                                // Found a xmlns: attribute
                                                // Extract the namespace string
                                                // Add heading to the HTML according to which Namespace the RDF items have
                                                switch ( substr( $key,6) )
                                                {
                                                        case "photoshop":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Photoshop RDF Segment</h3>\n";
                                                                break;
                                                        case "xapBJ":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Basic Job Ticket RDF Segment</h3>\n";
                                                                break;
                                                        case "xapMM":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Media Management RDF Segment</h3>\n";
                                                                break;
                                                        case "xapRights":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Rights Management RDF Segment</h3>\n";
                                                                break;
                                                        case "dc":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Dublin Core Metadata Initiative RDF Segment</h3>\n";
                                                                break;
                                                        case "xmp":
                                                        case "xap":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">XMP Basic Segment</h3>\n";
                                                                break;
                                                        case "xmpTPg":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">XMP Paged-Text Segment</h3>\n";
                                                                break;
                                                        case "xmpTPg":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Adobe PDF Segment</h3>\n";
                                                                break;
                                                        case "tiff":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">XMP - embedded TIFF Segment</h3>\n";
                                                                break;
                                                        case "exif":
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">XMP - embedded EXIF Segment</h3>\n";
                                                                break;
                                                        case "xapGImg":  // Sub Category - Do nothing
                                                                break;
                                                        case "stDim":  // Sub Category - Do nothing
                                                                break;
                                                        case "stEvt":  // Sub Category - Do nothing
                                                                break;
                                                        case "stRef":  // Sub Category - Do nothing
                                                                break;
                                                        case "stVer":  // Sub Category - Do nothing
                                                                break;
                                                        case "stJob":  // Sub Category - Do nothing
                                                                break;

                                                        default:
                                                                $output .= "<h3 class=\"XMP_Secondary_Heading\">Unknown RDF Segment '" . substr( $key,6) . "'</h3>\n";
                                                                break;
                                                }


                                        }

                                }

                                // Add the start of the table to the HTML output
                                $output .= "\n<table  class=\"XMP_Table\" border=1>\n";


                                // Check if this element has sub-items

                                if ( array_key_exists( 'children', $RDF_Item ) )
                                {

                                        // Cycle through each of the sub-items
                                        foreach( $RDF_Item['children'] as $child_item )
                                        {
                                                // Get an interpretation of the sub-item's caption and value
                                                list($tag_caption, $value_str) = Interpret_RDF_Item( $child_item );

                                                // Escape the text of the caption for html
                                                $tag_caption = HTML_UTF8_Escape( $tag_caption );
                                                // Escape the text of the value for html and turn newlines to <br>
                                                $value_str = nl2br( HTML_UTF8_Escape( $value_str ) );

                                                // Check if the value is empty - if it is, put a no-break-space in
                                                // to ensure the table cell gets drawn
                                                if ( $value_str == "" )
                                                {
                                                        $value_str = "&nbsp;";
                                                }
                                                // Add the table row to the output html
                                                $output .= "<tr class=\"XMP_Table_Row\"><td  class=\"XMP_Caption_Cell\">" . $tag_caption . ":</td><td  class=\"XMP_Value_Cell\">" . $value_str . "</td></tr>\n";
                                        }
                                }

                                // Add the end of the table to the html
                                $output .= "\n</table>\n";


                        }
                        else
                        {
                                // Don't know how to process tags other than rdf:Description - do nothing
                        }
                }



        }
        // Return the resulting HTML
        return $output;
}

/******************************************************************************
* End of Function:     Interpret_XMP_to_HTML
******************************************************************************/


















/******************************************************************************
*
*         INTERNAL FUNCTIONS
*
******************************************************************************/












/******************************************************************************
*
* Internal Function:     Interpret_RDF_Item
*
* Description:  Used by Interpret_XMP_to_HTML
*               Used by get_RDF_field_html_value
*               Used by interpret_RDF_collection
*               Generates a caption and text representation of the value of a
*               particular RDF item.
*
* Parameters:   Item - The RDF item to evaluate
*
* Returns:      tag_caption - the caption of the tag
*               value_str - the text representation of the value
*
******************************************************************************/

function Interpret_RDF_Item( $Item )
{

        // TODO: Many RDF items have not been tested - only photoshop 7.0 and CS items

        // Create a string to receive the HTML output
        $value_str = "";

        // Check if the item has is in the lookup table of tag captions
        if ( array_key_exists( $Item['tag'], $GLOBALS[ 'XMP_tag_captions' ] ) )
        {
                // Item is in list of captions, get the caption
                $tag_caption = $GLOBALS[ 'XMP_tag_captions' ][ $Item['tag'] ];
        }
        else
        {
                // Item has no caption - make one
                $tag_caption = "Unknown field " . $Item['tag'];
        }


        // Process specially the item according to it's tag
        switch ( $Item['tag'] )
        {

                case "photoshop:DateCreated":            // This is in year month day order
                        // Extract the year,month and day
                        list( $year, $month, $day ) = sscanf( $Item['value'], "%d-%d-%d" );
                        // Make a new date string with Day, Month, Year
                        $value_str = "$day/$month/$year";
                        break;

                default :
                        $value_str = get_RDF_field_html_value( $Item );
                        break;
        }




        // Return the captiona and value
        return array($tag_caption, $value_str);
}


/******************************************************************************
* End of Function:     Interpret_RDF_Item
******************************************************************************/





/******************************************************************************
*
* Internal Function:     get_RDF_field_html_value
*
* Description:  Attempts to build a text representation of the value of an RDF
*               item. This includes handling any collections or sub-resources.
*
* Parameters:   rdf_item - The RDF item to evaluate
*
* Returns:      output_str - the text representation of the field value
*
******************************************************************************/

function get_RDF_field_html_value( $rdf_item )
{
        // Create a string to receive the output text
        $output_str = "";

        // Check if the item has a value
        if ( array_key_exists( 'value', $rdf_item ) )
        {
                // The item does have a value - add it to the text
                $output_str .= $rdf_item['value'];
        }

        // Check if the item has any attributes
        if ( array_key_exists( 'attributes', $rdf_item ) )
        {
                // Cycle through each of the attributes
                foreach( $rdf_item['attributes'] as $key => $val )
                {
                        // Check if this attribute is rdf:parseType = 'Resource' i.e. a sub-resource indicator
                        if ( ( $key == "rdf:parseType" ) && ( $val == "Resource" ) )
                        {
                                // This item has a attribute indicating sub-resources
                                // Check that the item has sub items
                                if ( array_key_exists( 'children', $rdf_item ) )
                                {
                                        // The item does have sub-items,
                                        // Cycle through each, Interpreting them and adding the result to the output text
                                        foreach( $rdf_item['children'] as $child )
                                        {
                                                list($tag_caption, $value_str) = Interpret_RDF_Item( $child );
                                                $output_str .= "$tag_caption  =  $value_str\n";
                                        }
                                        // The output text will have an extra \n on it - remove it
                                        $output_str = rtrim( $output_str );
                                }
                        }
                }
        }
                // If the item did not have sub-resources, it may still have sub-items - check for this
        else if ( array_key_exists( 'children', $rdf_item ) )
        {
                // Non-resource Sub-items found, Cycle through each
                foreach( $rdf_item['children'] as $child_item )
                {
                        // Check if this sub-item has a tag
                        if ( array_key_exists( 'tag', $child_item ) )
                        {
                                // Sub item has a tag, Process it according to the tag
                                switch ( $child_item[ 'tag' ] )
                                {
                                        // Collections
                                        case "rdf:Alt":
                                                $output_str .= "List of Alternates:\n";
                                                $output_str .= interpret_RDF_collection( $child_item );
                                                break;

                                        case "rdf:Bag":
                                                $output_str .= "Unordered List:\n";
                                                $output_str .= interpret_RDF_collection( $child_item );
                                                break;

                                        case "rdf:Seq":
                                                $output_str .= "Ordered List:\n";
                                                $output_str .= interpret_RDF_collection( $child_item );
                                                break;

                                        // Sub-Resource
                                        case "rdf:Description":
                                                // Check that the item has sub items
                                                if ( array_key_exists( 'children', $child_item ) )
                                                {
                                                        // The item does have sub-items,
                                                        // Cycle through each, Interpreting them and adding the result to the output text
                                                        foreach( $child_item['children'] as $child )
                                                        {
                                                                list($tag_caption, $value_str) = Interpret_RDF_Item( $child );
                                                                $output_str .= "$tag_caption  =  $value_str\n";
                                                        }
                                                        // The output text will have an extra \n on it - remove it
                                                        $output_str = rtrim( $output_str );
                                                }
                                                break;

                                        // Other
                                        default:
                                                $output_str .= "Unknown Sub Item type:". $child_item[ 'tag' ]. "\n";
                                                break;
                                }
                        } // sub-item Has no tags, look for a value
                        else if ( array_key_exists( 'value', $child_item ) )
                        {
                                $output_str .= $rdf_item['value'] . "\n";
                        }
                        else
                        {
                                // no info - do nothing
                        }

                }
        }

        // return the resulting value string
        return $output_str;
}

/******************************************************************************
* End of Function:     get_RDF_field_html_value
******************************************************************************/








/******************************************************************************
*
* Internal Function:     interpret_RDF_collection
*
* Description:  Attempts to build a text representation of the value of an RDF
*               collection item. This includes handling any sub-collections or
*               sub-resources.
*
* Parameters:   rdf_item - The RDF collection item to evaluate
*
* Returns:      output_str - the text representation of the collection value
*
******************************************************************************/

function interpret_RDF_collection( $item )
{
        // Create a string to receive the output
        $output_str = "";

        // Check if the collection item has sub-items
        if ( array_key_exists( 'children', $item ) )
        {

                // Cycle through each of the sub-items
                foreach( $item['children'] as $list_item )
                {
                        // Check that the sub item has a tag, and don't process it if it doesn't
                        if ( ! array_key_exists( 'tag', $list_item ) )
                        {
                                continue 1;
                        }

                        // Check that the sub-item tag is either rdf:li or rdf:_1 ....
                        // This signifies it is a list item of the collection
                        if ( ( $list_item['tag'] == "rdf:li" ) ||
                             ( preg_match ( "rdf:_\d+", $list_item['tag'] ) == 1 ) )
                        {
                                // A List item has been found
                                // Check if there are sub-resources,
                                // starting by checking if there are attributes
                                if ( array_key_exists( 'attributes', $list_item ) )
                                {
                                        // Cycle through each of the attributes
                                        foreach( $list_item['attributes'] as $key => $val )
                                        {
                                                // Check if this attribute is rdf:parseType = 'Resource' i.e. a sub-resource indicator
                                                if ( ( $key == "rdf:parseType" ) && ( $val == "Resource" ) )
                                                {
                                                        // This item has a attribute indicating sub-resources
                                                        // Check that the item has sub items
                                                        if ( array_key_exists( 'children', $list_item ) )
                                                        {
                                                                // The item does have sub-items,
                                                                // Cycle through each, Interpreting them and adding the result to the output text
                                                                foreach( $list_item['children'] as $child )
                                                                {
                                                                        list($tag_caption, $value_str) = Interpret_RDF_Item( $child );
                                                                        $output_str .= "$tag_caption  =  $value_str\n";
                                                                }
                                                                // The output text will have an extra \n on it - remove it
                                                                $output_str = rtrim( $output_str );
                                                        }
                                                }
                                        }
                                }

                                // Check if the list item has a value
                                if ( array_key_exists( 'value', $list_item ) )
                                {
                                        // Value found, add it to the output
                                        $output_str .= get_RDF_field_html_value( $list_item ) . "\n";
                                }

                        }
                }
                // The list of sub-items formed will have a trailing \n, remove it.
                $output_str = rtrim( $output_str );

        }
        else
        {
                // No sub-items in collection - can't do anything
        }

        // Return the output value
        return $output_str;

}

/******************************************************************************
* End of Function:     interpret_RDF_collection
******************************************************************************/















/******************************************************************************
* Global Variable:      XMP_tag_captions
*
* Contents:     The Captions of the known XMP fields, indexed by their field name
*
******************************************************************************/

$GLOBALS[ 'XMP_tag_captions' ] = array (

"dc:contributor" => "Other Contributor(s)",
"dc:coverage" => "Coverage (scope)",
"dc:creator" => "Creator(s) (Authors)",
"dc:date" => "Date",
"dc:description" => "Description (Caption)",
"dc:format" => "MIME Data Format",
"dc:identifier" => "Unique Resource Identifer",
"dc:language" => "Language(s)",
"dc:publisher" => "Publisher(s)",
"dc:relation" => "Relations to other documents",
"dc:rights" => "Rights Statement",
"dc:source" => "Source (from which this Resource is derived)",
"dc:subject" => "Subject and Keywords",
"dc:title" => "Title",
"dc:type" => "Resource Type",

"xmp:Advisory" => "Externally Editied Properties",
"xmp:BaseURL" => "Base URL for relative URL's",
"xmp:CreateDate" => "Original Creation Date",
"xmp:CreatorTool" => "Creator Tool",
"xmp:Identifier" => "Identifier(s)",
"xmp:MetadataDate" => "Metadata Last Modify Date",
"xmp:ModifyDate" => "Resource Last Modify Date",
"xmp:Nickname" => "Nickname",
"xmp:Thumbnails" => "Thumbnails",

"xmpidq:Scheme" => "Identification Scheme",

// These are not in spec but Photoshop CS seems to use them
"xap:Advisory" => "Externally Editied Properties",
"xap:BaseURL" => "Base URL for relative URL's",
"xap:CreateDate" => "Original Creation Date",
"xap:CreatorTool" => "Creator Tool",
"xap:Identifier" => "Identifier(s)",
"xap:MetadataDate" => "Metadata Last Modify Date",
"xap:ModifyDate" => "Resource Last Modify Date",
"xap:Nickname" => "Nickname",
"xap:Thumbnails" => "Thumbnails",
"xapidq:Scheme" => "Identification Scheme",


"xapRights:Certificate" => "Certificate",
"xapRights:Copyright" => "Copyright",
"xapRights:Marked" => "Marked",
"xapRights:Owner" => "Owner",
"xapRights:UsageTerms" => "Legal Terms of Usage",
"xapRights:WebStatement" => "Web Page describing rights statement (Owner URL)",

"xapMM:ContainedResources" => "Contained Resources",
"xapMM:ContributorResources" => "Contributor Resources",
"xapMM:DerivedFrom" => "Derived From",
"xapMM:DocumentID" => "Document ID",
"xapMM:History" => "History",
"xapMM:LastURL" => "Last Written URL",
"xapMM:ManagedFrom" => "Managed From",
"xapMM:Manager" => "Asset Management System",
"xapMM:ManageTo" => "Manage To",
"xapMM:xmpMM:ManageUI" => "Managed Resource URI",
"xapMM:ManagerVariant" => "Particular Variant of Asset Management System",
"xapMM:RenditionClass" => "Rendition Class",
"xapMM:RenditionParams" => "Rendition Parameters",
"xapMM:RenditionOf" => "Rendition Of",
"xapMM:SaveID" => "Save ID",
"xapMM:VersionID" => "Version ID",
"xapMM:Versions" => "Versions",

"xapBJ:JobRef" => "Job Reference",

"xmpTPg:MaxPageSize" => "Largest Page Size",
"xmpTPg:NPages" => "Number of pages",

"pdf:Keywords" => "Keywords",
"pdf:PDFVersion" => "PDF file version",
"pdf:Producer" => "PDF Creation Tool",

"photoshop:AuthorsPosition" => "Authors Position",
"photoshop:CaptionWriter" => "Caption Writer",
"photoshop:Category" => "Category",
"photoshop:City" => "City",
"photoshop:Country" => "Country",
"photoshop:Credit" => "Credit",
"photoshop:DateCreated" => "Creation Date",
"photoshop:Headline" => "Headline",
"photoshop:History" => "History",                       // Not in XMP spec
"photoshop:Instructions" => "Instructions",
"photoshop:Source" => "Source",
"photoshop:State" => "State",
"photoshop:SupplementalCategories" => "Supplemental Categories",
"photoshop:TransmissionReference" => "Technical (Transmission) Reference",
"photoshop:Urgency" => "Urgency",


"tiff:ImageWidth" => "Image Width",
"tiff:ImageLength" => "Image Length",
"tiff:BitsPerSample" => "Bits Per Sample",
"tiff:Compression" => "Compression",
"tiff:PhotometricInterpretation" => "Photometric Interpretation",
"tiff:Orientation" => "Orientation",
"tiff:SamplesPerPixel" => "Samples Per Pixel",
"tiff:PlanarConfiguration" => "Planar Configuration",
"tiff:YCbCrSubSampling" => "YCbCr Sub-Sampling",
"tiff:YCbCrPositioning" => "YCbCr Positioning",
"tiff:XResolution" => "X Resolution",
"tiff:YResolution" => "Y Resolution",
"tiff:ResolutionUnit" => "Resolution Unit",
"tiff:TransferFunction" => "Transfer Function",
"tiff:WhitePoint" => "White Point",
"tiff:PrimaryChromaticities" => "Primary Chromaticities",
"tiff:YCbCrCoefficients" => "YCbCr Coefficients",
"tiff:ReferenceBlackWhite" => "Black & White Reference",
"tiff:DateTime" => "Date & Time",
"tiff:ImageDescription" => "Image Description",
"tiff:Make" => "Make",
"tiff:Model" => "Model",
"tiff:Software" => "Software",
"tiff:Artist" => "Artist",
"tiff:Copyright" => "Copyright",


"exif:ExifVersion" => "Exif Version",
"exif:FlashpixVersion" => "Flash pix Version",
"exif:ColorSpace" => "Color Space",
"exif:ComponentsConfiguration" => "Components Configuration",
"exif:CompressedBitsPerPixel" => "Compressed Bits Per Pixel",
"exif:PixelXDimension" => "Pixel X Dimension",
"exif:PixelYDimension" => "Pixel Y Dimension",
"exif:MakerNote" => "Maker Note",
"exif:UserComment" => "User Comment",
"exif:RelatedSoundFile" => "Related Sound File",
"exif:DateTimeOriginal" => "Date & Time of Original",
"exif:DateTimeDigitized" => "Date & Time Digitized",
"exif:ExposureTime" => "Exposure Time",
"exif:FNumber" => "F Number",
"exif:ExposureProgram" => "Exposure Program",
"exif:SpectralSensitivity" => "Spectral Sensitivity",
"exif:ISOSpeedRatings" => "ISO Speed Ratings",
"exif:OECF" => "Opto-Electronic Conversion Function",
"exif:ShutterSpeedValue" => "Shutter Speed Value",
"exif:ApertureValue" => "Aperture Value",
"exif:BrightnessValue" => "Brightness Value",
"exif:ExposureBiasValue" => "Exposure Bias Value",
"exif:MaxApertureValue" => "Max Aperture Value",
"exif:SubjectDistance" => "Subject Distance",
"exif:MeteringMode" => "Metering Mode",
"exif:LightSource" => "Light Source",
"exif:Flash" => "Flash",
"exif:FocalLength" => "Focal Length",
"exif:SubjectArea" => "Subject Area",
"exif:FlashEnergy" => "Flash Energy",
"exif:SpatialFrequencyResponse" => "Spatial Frequency Response",
"exif:FocalPlaneXResolution" => "Focal Plane X Resolution",
"exif:FocalPlaneYResolution" => "Focal Plane Y Resolution",
"exif:FocalPlaneResolutionUnit" => "Focal Plane Resolution Unit",
"exif:SubjectLocation" => "Subject Location",
"exif:SensingMethod" => "Sensing Method",
"exif:FileSource" => "File Source",
"exif:SceneType" => "Scene Type",
"exif:CFAPattern" => "Colour Filter Array Pattern",
"exif:CustomRendered" => "Custom Rendered",
"exif:ExposureMode" => "Exposure Mode",
"exif:WhiteBalance" => "White Balance",
"exif:DigitalZoomRatio" => "Digital Zoom Ratio",
"exif:FocalLengthIn35mmFilm" => "Focal Length In 35mm Film",
"exif:SceneCaptureType" => "Scene Capture Type",
"exif:GainControl" => "Gain Control",
"exif:Contrast" => "Contrast",
"exif:Saturation" => "Saturation",
"exif:Sharpness" => "Sharpness",
"exif:DeviceSettingDescription" => "Device Setting Description",
"exif:SubjectDistanceRange" => "Subject Distance Range",
"exif:ImageUniqueID" => "Image Unique ID",
"exif:GPSVersionID" => "GPS Version ID",
"exif:GPSLatitude" => "GPS Latitude",
"exif:GPSLongitude" => "GPS Longitude",
"exif:GPSAltitudeRef" => "GPS Altitude Reference",
"exif:GPSAltitude" => "GPS Altitude",
"exif:GPSTimeStamp" => "GPS Time Stamp",
"exif:GPSSatellites" => "GPS Satellites",
"exif:GPSStatus" => "GPS Status",
"exif:GPSMeasureMode" => "GPS Measure Mode",
"exif:GPSDOP" => "GPS Degree Of Precision",
"exif:GPSSpeedRef" => "GPS Speed Reference",
"exif:GPSSpeed" => "GPS Speed",
"exif:GPSTrackRef" => "GPS Track Reference",
"exif:GPSTrack" => "GPS Track",
"exif:GPSImgDirectionRef" => "GPS Image Direction Reference",
"exif:GPSImgDirection" => "GPS Image Direction",
"exif:GPSMapDatum" => "GPS Map Datum",
"exif:GPSDestLatitude" => "GPS Destination Latitude",
"exif:GPSDestLongitude" => "GPS Destnation Longitude",
"exif:GPSDestBearingRef" => "GPS Destination Bearing Reference",
"exif:GPSDestBearing" => "GPS Destination Bearing",
"exif:GPSDestDistanceRef" => "GPS Destination Distance Reference",
"exif:GPSDestDistance" => "GPS Destination Distance",
"exif:GPSProcessingMethod" => "GPS Processing Method",
"exif:GPSAreaInformation" => "GPS Area Information",
"exif:GPSDifferential" => "GPS Differential",

"stDim:w" => "Width",
"stDim:h" => "Height",
"stDim:unit" => "Units",

"xapGImg:height" => "Height",
"xapGImg:width" => "Width",
"xapGImg:format" => "Format",
"xapGImg:image" => "Image",

"stEvt:action" => "Action",
"stEvt:instanceID" => "Instance ID",
"stEvt:parameters" => "Parameters",
"stEvt:softwareAgent" => "Software Agent",
"stEvt:when" => "When",

"stRef:instanceID" => "Instance ID",
"stRef:documentID" => "Document ID",
"stRef:versionID" => "Version ID",
"stRef:renditionClass" => "Rendition Class",
"stRef:renditionParams" => "Rendition Parameters",
"stRef:manager" => "Asset Management System",
"stRef:managerVariant" => "Particular Variant of Asset Management System",
"stRef:manageTo" => "Manage To",
"stRef:manageUI" => "Managed Resource URI",

"stVer:comments" => "",
"stVer:event" => "",
"stVer:modifyDate" => "",
"stVer:modifier" => "",
"stVer:version" => "",



"stJob:name" => "Job Name",
"stJob:id" => "Unique Job ID",
"stJob:url" => "URL for External Job Management File",

// Exif Flash
"exif:Fired" => "Fired",
"exif:Return" => "Return",
"exif:Mode" => "Mode",
"exif:Function" => "Function",
"exif:RedEyeMode" => "Red Eye Mode",

// Exif OECF/SFR
"exif:Columns" => "Columns",
"exif:Rows" => "Rows",
"exif:Names" => "Names",
"exif:Values" => "Values",

// Exif CFAPattern
"exif:Columns" => "Columns",
"exif:Rows" => "Rows",
"exif:Values" => "Values",


// Exif DeviceSettings
"exif:Columns" => "Columns",
"exif:Rows" => "Rows",
"exif:Settings" => "Settings",



);

/******************************************************************************
* End of Global Variable:     XMP_tag_captions
******************************************************************************/


?>