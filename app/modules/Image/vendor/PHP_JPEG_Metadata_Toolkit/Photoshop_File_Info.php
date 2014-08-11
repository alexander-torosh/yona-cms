<?php

/******************************************************************************
*
* Filename:     Photoshop_File_Info.php
*
* Description:  Provides functions that mimic the way Photoshop reads and writes
*               metadata in it's 'File Info' dialog
*
* Author:       Evan Hunter
*
* Date:         11/11/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.11
* Changes:      1.10 -> 1.11 : Changed displayed toolkit version numbers to reference Toolkit_Version.php
*
* URL:          http://electronics.ozhiker.com
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

// TODO: XMP sections: XAPMM, TIFF, EXIF


include 'Toolkit_Version.php';          // Change: added as of version 1.11


/******************************************************************************
* Global Variable:      Software Name
*
* Contents:     The string that is appended to fields which store the name of
*               the software editor.
*
******************************************************************************/

$GLOBALS[ "Software Name" ] = "(PHP JPEG Metadata Toolkit v" . $GLOBALS['Toolkit_Version'] . ")";          // Change:  Changed version numbers to reference Toolkit_Version.php - as of version 1.11

/******************************************************************************
* End of Global Variable:     Software Name
******************************************************************************/






/******************************************************************************
*
* Function:     get_photoshop_file_info
*
* Description:  Retrieves Photoshop 'File Info' metadata in the same way that Photoshop
*               does. The results are returned in an array as below:
*
*               $file_info_array = array(
*                       "title"                  => "",
*                       "author"                 => "",
*                       "authorsposition"        => "",      // Note: Not used in Photoshop 7 or higher
*                       "caption"                => "",
*                       "captionwriter"          => "",
*                       "jobname"                => "",      // Note: Not used in Photoshop CS
*                       "copyrightstatus"        => "",
*                       "copyrightnotice"        => "",
*                       "ownerurl"               => "",
*                       "keywords"               => array( 0 => "", 1 => "", ... ),
*                       "category"               => "",     // Note: Max 3 characters
*                       "supplementalcategories" => array( 0 => "", 1 => "", ... ),
*                       "date"                   => "",     // Note: DATE MUST BE IN YYYY-MM-DD format
*                       "city"                   => "",
*                       "state"                  => "",
*                       "country"                => "",
*                       "credit"                 => "",
*                       "source"                 => "",
*                       "headline"               => "",
*                       "instructions"           => "",
*                       "transmissionreference"  => "",
*                       "urgency"                => "" );
*
* Parameters:   Exif_array - an array containing the EXIF information to be
*                            searched, as retrieved by get_EXIF_JPEG. (saves having to parse the EXIF again)
*               XMP_array - an array containing the XMP information to be
*                           searched, as retrieved by read_XMP_array_from_text. (saves having to parse the XMP again)
*               IRB_array - an array containing the Photoshop IRB information
*                           to be searched, as retrieved by get_Photoshop_IRB. (saves having to parse the IRB again)
*
* Returns:      outputarray - an array as above, containing the Photoshop File Info data
*
******************************************************************************/

function get_photoshop_file_info( $Exif_array, $XMP_array, $IRB_array )
{

        // Create a blank array to receive the output
        $outputarray = array(
                "title" => "",
                "author" => "",
                "authorsposition" => "",
                "caption" => "",
                "captionwriter" => "",
                "jobname" => "",
                "copyrightstatus" => "",
                "copyrightnotice" => "",
                "ownerurl" => "",
                "keywords" => array(),
                "category" => "",
                "supplementalcategories" => array(),
                "date" => "",
                "city" => "",
                "state" => "",
                "country" => "",
                "credit" => "",
                "source" => "",
                "headline" => "",
                "instructions" => "",
                "transmissionreference" => "",
                "urgency" => "" );


        /***************************************/

        // XMP Processing


        // Retrieve the dublin core section from the XMP header

        // Extract the Dublin Core section from the XMP
        $dublincore_block = find_XMP_block( $XMP_array, "dc" );

        // Check that the Dublin Core section exists
        if ( $dublincore_block != FALSE )
        {
                // Dublin Core Description Field contains caption
                // Extract Description
                $Item = find_XMP_item( $dublincore_block, "dc:description" );

                // Check if Description Tag existed
                if ( $Item != FALSE )
                {
                        // Ensure that the Description value exists and save it.
                        if  ( ( array_key_exists( 'children', $Item ) ) &&
                              ( $Item['children'][0]['tag'] == "rdf:Alt" ) &&
                              ( array_key_exists( 'value', $Item['children'][0]['children'][0] ) ) )
                        {
                                $outputarray = add_to_field( $outputarray, 'caption' , HTML_UTF8_Escape( $Item['children'][0]['children'][0]['value'] ), "\n" );
                        }
                }

                /***************************************/

                // Dublin Core Creator Field contains author
                // Extract Description
                $Item = find_XMP_item( $dublincore_block, "dc:creator" );

                // Check if Creator Tag existed
                if ( $Item != FALSE )
                {
                        // Ensure that the Creator value exists and save it.
                        if  ( ( array_key_exists( 'children', $Item ) ) &&
                              ( $Item['children'][0]['tag'] =="rdf:Seq" ) &&
                              ( array_key_exists( 'value', $Item['children'][0]['children'][0] ) ) )
                        {
                                $outputarray = add_to_field( $outputarray, 'author' , HTML_UTF8_Escape( $Item['children'][0]['children'][0]['value'] ), "\n" );
                        }
                }

                /***************************************/

                // Dublin Core Title Field contains title
                // Extract Title
                $Item = find_XMP_item( $dublincore_block, "dc:title" );

                // Check if Title Tag existed
                if ( $Item != FALSE )
                {
                        // Ensure that the Title value exists and save it.
                        if  ( ( array_key_exists( 'children', $Item ) ) &&
                              ( $Item['children'][0]['tag'] =="rdf:Alt" ) &&
                              ( array_key_exists( 'value', $Item['children'][0]['children'][0] ) ) )
                        {

                                $outputarray = add_to_field( $outputarray, 'title' , HTML_UTF8_Escape( $Item['children'][0]['children'][0]['value'] ), "," );
                        }
                }

                /***************************************/

                // Dublin Core Rights Field contains copyrightnotice
                // Extract Rights
                $Item = find_XMP_item( $dublincore_block, "dc:rights" );

                // Check if Rights Tag existed
                if ( $Item != FALSE )
                {
                        // Ensure that the Rights value exists and save it.
                        if  ( ( array_key_exists( 'children', $Item ) ) &&
                              ( $Item['children'][0]['tag'] =="rdf:Alt" ) &&
                              ( array_key_exists( 'value', $Item['children'][0]['children'][0] ) ) )
                        {

                                $outputarray = add_to_field( $outputarray, 'copyrightnotice' , HTML_UTF8_Escape( $Item['children'][0]['children'][0]['value'] ), "," );
                        }
                }

                /***************************************/

                // Dublin Core Subject Field contains keywords
                // Extract Subject
                $Item = find_XMP_item( $dublincore_block, "dc:subject" );

                // Check if Subject Tag existed
                if ( $Item != FALSE )
                {
                        // Ensure that the Subject values exist
                        if  ( ( array_key_exists( 'children', $Item ) ) && ( $Item['children'][0]['tag'] =="rdf:Bag" ) )
                        {
                                // Cycle through each Subject value and save them
                                foreach ( $Item['children'][0]['children'] as $keywords )
                                {
                                        if ( ! in_array ( HTML_UTF8_Escape( $keywords['value'] ), $outputarray['keywords']))
                                        {
                                                if  ( array_key_exists( 'value', $keywords ) )
                                                {
                                                        $outputarray['keywords'][] = HTML_UTF8_Escape( $keywords['value'] );
                                                }
                                        }
                                }
                        }
                }


        }

        /***************************************/

        // Find the Photoshop Information within the XMP block
        $photoshop_block = find_XMP_block( $XMP_array, "photoshop" );

        // Check that the Photoshop Information exists
        if ( $photoshop_block != FALSE )
        {
                // The Photoshop CaptionWriter tag contains captionwriter - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:CaptionWriter" );

                // Check that the CaptionWriter Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'captionwriter' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Headline tag contains headline - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Headline" );

                // Check that the Headline Field exists and save the value
                if ( ( $Item != FALSE )  && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'headline' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Instructions tag contains instructions - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Instructions" );

                // Check that the Instructions Field exists and save the value
                if ( ( $Item != FALSE )  && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'instructions' , HTML_UTF8_Escape( $Item['value'] ), "\n" );
                }

                /***************************************/

                // The Photoshop AuthorsPosition tag contains authorsposition - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:AuthorsPosition" );

                // Check that the AuthorsPosition Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'authorsposition' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Credit tag contains credit - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Credit" );

                // Check that the Credit Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'credit' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Source tag contains source - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Source" );

                // Check that the Credit Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'source' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop City tag contains city - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:City" );

                // Check that the City Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'city' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop State tag contains state - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:State" );

                // Check that the State Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'state' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Country tag contains country - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Country" );

                // Check that the Country Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'country' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop TransmissionReference tag contains transmissionreference - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:TransmissionReference" );

                // Check that the TransmissionReference Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'transmissionreference' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Category tag contains category - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Category" );

                // Check that the TransmissionReference Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'category' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop DateCreated tag contains date - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:DateCreated" );

                // Check that the DateCreated Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'date' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop Urgency tag contains urgency - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:Urgency" );

                // Check that the Urgency Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'urgency' , HTML_UTF8_Escape( $Item['value'] ), "," );
                }

                /***************************************/

                // The Photoshop SupplementalCategories tag contains supplementalcategories - Find it
                $Item = find_XMP_item( $photoshop_block, "photoshop:SupplementalCategories" );

                // Check that the SupplementalCategories Field exists
                if ( $Item != FALSE )
                {
                        // Check that the values exist
                        if  ( ( array_key_exists( 'children', $Item ) ) && ( $Item['children'][0]['tag'] =="rdf:Bag" ) )
                        {
                                // Cycle through the values and save them
                                foreach ( $Item['children'][0]['children'] as $sup_category )
                                {
                                        if ( ( array_key_exists( 'value', $sup_category ) ) &&
                                             ( ! in_array ( HTML_UTF8_Escape( $sup_category['value'] ), $outputarray['supplementalcategories'])) )
                                        {
                                                if ( array_key_exists( 'value', $sup_category ) )
                                                {
                                                        $outputarray['supplementalcategories'][] = HTML_UTF8_Escape( $sup_category['value'] );
                                                }
                                        }
                                }
                        }
                }

        }

        /***************************************/

        // Find the Job Reference Information within the XMP block
        $job_block = find_XMP_block( $XMP_array, "xapBJ" );

        // Check that the Job Reference Information exists
        if ( $job_block != FALSE )
        {
                // The JobRef Field contains jobname - Find it
                $Item = find_XMP_item( $job_block, "xapBJ:JobRef" );

                // Check that the JobRef Field exists
                if ( $Item != FALSE )
                {
                        // Check that the value exists and save it
                        if ( ( array_key_exists( 'children', $Item ) ) &&
                             ( $Item['children'][0]['tag'] =="rdf:Bag" ) &&
                             ( array_key_exists( 'children', $Item['children'][0] ) ) &&
                             ( $Item['children'][0]['children'][0]['tag'] =="rdf:li" ) &&
                             ( array_key_exists( 'children', $Item['children'][0]['children'][0] ) ) &&
                             ( $Item['children'][0]['children'][0]['children'][0]['tag'] =="stJob:name" ) &&
                             ( array_key_exists( 'value', $Item['children'][0]['children'][0]['children'][0] ) ) )
                        {
                                $outputarray = add_to_field( $outputarray, 'jobname' , HTML_UTF8_Escape( $Item['children'][0]['children'][0]['children'][0]['value'] ), "," );
                        }
                }
        }


        /***************************************/

        // Find the Rights Information within the XMP block
        $rights_block = find_XMP_block( $XMP_array, "xapRights" );

        // Check that the Rights Information exists
        if ( $rights_block != FALSE )
        {
                // The WebStatement Field contains ownerurl - Find it
                $Item = find_XMP_item( $rights_block, "xapRights:WebStatement" );

                // Check that the WebStatement Field exists and save the value
                if ( ( $Item != FALSE )  && ( array_key_exists( 'value', $Item ) ) )
                {
                        $outputarray = add_to_field( $outputarray, 'ownerurl' , HTML_UTF8_Escape( $Item['value'] ), "\n" );
                }

                /***************************************/

                // The Marked Field contains copyrightstatus - Find it
                $Item = find_XMP_item( $rights_block, "xapRights:Marked" );

                // Check that the Marked Field exists and save the value
                if ( ( $Item != FALSE ) && ( array_key_exists( 'value', $Item ) ) )
                {
                        if ( $Item['value'] == "True" )
                        {
                                $outputarray = add_to_field( $outputarray, 'copyrightstatus' , "Copyrighted Work", "," );
                        }
                        else
                        {
                                $outputarray = add_to_field( $outputarray, 'copyrightstatus' , "Public Domain", "," );
                        }
                }

        }





        /***************************************/

        // Photoshop IRB Processing

        // Check that the Photoshop IRB exists
        if ( $IRB_array != FALSE )
        {
                // Create a translation table to convert carriage returns to linefeeds
                $irbtrans = array("\x0d" => "\x0a");

                // The Photoshop IRB Copyright flag (0x040A) contains copyrightstatus - find it
                $IRB_copyright_flag = find_Photoshop_IRB_Resource( $IRB_array, 0x040A );

                // Check if the Copyright flag Field exists, and save the value
                if( $IRB_copyright_flag != FALSE )
                {
                        // Check the value of the copyright flag
                        if ( hexdec( bin2hex( $IRB_copyright_flag['ResData'] ) ) == 1 )
                        {
                                // Save the value
                                $outputarray = add_to_field( $outputarray, 'copyrightstatus' , "Copyrighted Work", "," );
                        }
                        else
                        {
                                // Do nothing - copyrightstatus will be set to unmarked if still blank at end
                        }
                }

                /***************************************/

                // The Photoshop IRB URL (0x040B) contains ownerurl - find it
                $IRB_url = find_Photoshop_IRB_Resource( $IRB_array, 0x040B );

                // Check if the URL Field exists and save the value
                if( $IRB_url != FALSE )
                {
                        $outputarray = add_to_field( $outputarray, 'ownerurl' , strtr( $IRB_url['ResData'], $irbtrans ), "\n" );
                }

                /***************************************/

                // Extract any IPTC block from the Photoshop IRB information
                $IPTC_array = get_Photoshop_IPTC( $IRB_array );

                // Check if the IPTC block exits
                if ( ( $IPTC_array != FALSE ) && ( count( $IPTC_array ) != 0 ) )
                {
                        // The IPTC Caption/Abstract Field contains caption - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:120" );

                        // Check if the Caption/Abstract Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'caption' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Caption Writer/Editor Field contains captionwriter - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:122" );

                        // Check if the Caption Writer/Editor Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'captionwriter' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Headline Field contains headline - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:105" );

                        // Check if the Headline Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'headline' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Special Instructions Field contains instructions - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:40" );

                        // Check if the Special Instructions Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'instructions' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC By-Line Field contains author - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:80" );

                        // Check if the By-Line Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'author' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC By-Line Title Field contains authorsposition - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:85" );

                        // Check if the By-Line Title Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'authorsposition' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Credit Field contains credit - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:110" );

                        // Check if the Credit Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'credit' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Source Field contains source - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:115" );

                        // Check if the Source Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'source' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Object Name Field contains title - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:05" );

                        // Check if the Object Name Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'title' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Date Created Field contains date - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:55" );

                        // Check if the Date Created Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $date_array = unpack( "a4Year/a2Month/A2Day", $record['RecData'] );
                                $tmpdate = $date_array['Year'] . "-" . $date_array['Month'] . "-" . $date_array['Day'];
                                $outputarray = add_to_field( $outputarray, 'date' , strtr( $tmpdate, $irbtrans ), "," );

                        }

                        /***************************************/

                        // The IPTC City Field contains city - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:90" );

                        // Check if the City Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'city' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Province/State Field contains state - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:95" );

                        // Check if the Province/State Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'state' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Country/Primary Location Name Field contains country - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:101" );

                        // Check if the Country/Primary Location Name Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'country' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Original Transmission Reference Field contains transmissionreference - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:103" );

                        // Check if the Original Transmission Reference Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'transmissionreference' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                        /***************************************/

                        // The IPTC Category Field contains category - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:15" );

                        // Check if the Category Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'category' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }


                        /***************************************/

                        // Cycle through the IPTC records looking for Supplemental Category records
                        foreach ($IPTC_array as $record)
                        {
                                // Check if a Supplemental Category record has been found
                                if ( $record['IPTC_Type'] == "2:20" )
                                {
                                        // A Supplemental Category record has been found, save it's value if the value doesn't already exist
                                        if ( ! in_array ( $record['RecData'], $outputarray['supplementalcategories']))
                                        {
                                                $outputarray['supplementalcategories'][] = strtr( $record['RecData'], array("\x0a" => "", "\x0d" => "&#xA;") ) ;
                                        }
                                }
                        }


                        /***************************************/

                        // The IPTC Urgency Field contains urgency - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:10" );

                        // Check if the Urgency Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'urgency' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }



                        /***************************************/

                        // Cycle through the IPTC records looking for Keywords records
                        foreach ($IPTC_array as $record)
                        {
                                // Check if a Keywords record has been found
                                if ( $record['IPTC_Type'] == "2:25" )
                                {
                                        // A Keywords record has been found, save it's value if the value doesn't already exist
                                        if ( ! in_array ( $record['RecData'], $outputarray['keywords']))
                                        {
                                                $outputarray['keywords'][] = strtr( $record['RecData'], array("\x0a" => "", "\x0d" => "&#xA;") ) ;
                                        }
                                }
                        }


                        /***************************************/

                        // The IPTC Copyright Notice Field contains copyrightnotice - find it
                        $record = find_IPTC_Resource( $IPTC_array, "2:116" );

                        // Check if the Copyright Field exists and save the value
                        if ( $record != FALSE  )
                        {
                                $outputarray = add_to_field( $outputarray, 'copyrightnotice' , strtr( $record['RecData'], $irbtrans ), "\n" );
                        }

                }
        }




        /***************************************/

        // EXIF Processing


        // Retreive Information from the EXIF data if it exists

        if ( ( $Exif_array != FALSE ) || ( count( $Exif_array ) == 0 ) )
        {
                // Check the Image Description Tag - it can contain the caption
                if ( array_key_exists( 270, $Exif_array[0] ) )
                {
                        $outputarray = add_to_field( $outputarray, 'caption' , $Exif_array[0][270]['Data'][0], "\n" );
                }

                /***************************************/

                // Check the Copyright Information Tag - it contains the copyrightnotice
                if ( array_key_exists( 33432, $Exif_array[0] ) )
                {
                        $outputarray = add_to_field( $outputarray, 'copyrightnotice' , HTML_UTF8_UnEscape( $Exif_array[0][33432]['Data'][0] ), "\n" );
                }

                /***************************************/

                // Check the Artist Name Tag - it contains the author
                if ( array_key_exists( 315, $Exif_array[0] ) )
                {
                        $outputarray = add_to_field( $outputarray, 'author' , HTML_UTF8_UnEscape( $Exif_array[0][315]['Data'][0] ), "\n" );
                }

        }


        /***************************/

        // FINISHED RETRIEVING INFORMATION

        // Perform final processing


        // Check if any urgency information was found
        if ( $outputarray["urgency"] == "" )
        {
                // No urgency information was found - set it to default (None)
                $outputarray["urgency"] = "none";
        }

        // Check if any copyrightstatus information was found
        if ( $outputarray["copyrightstatus"] == "" )
        {
                // No copyrightstatus information was found - set it to default (Unmarked)
                $outputarray["copyrightstatus"] = "unmarked";
        }

        // Return the resulting Photoshop File Info Array
        return $outputarray;

}

/******************************************************************************
* End of Function:     get_photoshop_file_info
******************************************************************************/






/******************************************************************************
*
* Function:     put_photoshop_file_info
*
* Description:  Stores Photoshop 'File Info' metadata in the same way that Photoshop
*               does. The 'File Info' metadata must be in an array similar to that
*               returned by get_photoshop_file_info, as follows:
*
*               $file_info_array = array(
*                       "title"                  => "",
*                       "author"                 => "",
*                       "authorsposition"        => "",      // Note: Not used in Photoshop 7 or higher
*                       "caption"                => "",
*                       "captionwriter"          => "",
*                       "jobname"                => "",      // Note: Not used in Photoshop CS
*                       "copyrightstatus"        => "",
*                       "copyrightnotice"        => "",
*                       "ownerurl"               => "",
*                       "keywords"               => array( 0 => "", 1 => "", ... ),
*                       "category"               => "",     // Note: Max 3 characters
*                        "supplementalcategories" => array( 0 => "", 1 => "", ... ),
*                       "date"                   => "",     // Note: DATE MUST BE IN YYYY-MM-DD format
*                       "city"                   => "",
*                       "state"                  => "",
*                       "country"                => "",
*                       "credit"                 => "",
*                       "source"                 => "",
*                       "headline"               => "",
*                       "instructions"           => "",
*                       "transmissionreference"  => "",
*                       "urgency"                => "" );
*
* Parameters:   jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data. This contains the
*                                  header information which is to be updated.
*               new_ps_file_info_array - An array as above, which contains the
*                                        'File Info' metadata information to be
*                                        written.
*               Old_Exif_array - an array containing the EXIF information to be
*                                updated, as retrieved by get_EXIF_JPEG. (saves having to parse the EXIF again)
*               Old_XMP_array - an array containing the XMP information to be
*                               updated, as retrieved by read_XMP_array_from_text. (saves having to parse the XMP again)
*               Old_IRB_array - an array containing the Photoshop IRB information
*                                to be updated, as retrieved by get_Photoshop_IRB. (saves having to parse the IRB again)
*
* Returns:      jpeg_header_data - a JPEG header data array in the same format
*                                  as from get_jpeg_header_data, containing the
*                                  Photshop 'File Info' metadata. This can then
*                                  be written to a file using put_jpeg_header_data.
*
******************************************************************************/

function put_photoshop_file_info( $jpeg_header_data, $new_ps_file_info_array, $Old_Exif_array, $Old_XMP_array, $Old_IRB_array )
{
        /*******************************************/
        // PREPROCESSING

        // Check that the date is in the correct format (YYYY-MM-DD)

        // Explode the date into pieces using the - symbol
        $date_pieces = explode( "-", $new_ps_file_info_array[ 'date' ] );

        // If there are not 3 pieces to the date, it is invalid
        if ( count( $date_pieces ) != 3 )
        {
                // INVALID DATE
                echo "Invalid Date - must be YYYY-MM-DD format<br>";
                return FALSE;
        }

        // Cycle through each piece of the date
        foreach( $date_pieces as $piece )
        {
                // If the piece is not numeric, then the date is invalid.
                if ( ! is_numeric( $piece ) )
                {
                        // INVALID DATE
                        echo "Invalid Date - must be YYYY-MM-DD format<br>";
                        return FALSE;
                }
        }

        // Make a unix timestamp at midnight on the date specified
        $date_stamp = mktime( 0,0,0, $date_pieces[1], $date_pieces[2], $date_pieces[0] );




        // Create a translation table to remove carriage return characters
        $trans = array( "\x0d" => "" );

        // Cycle through each of the File Info elements
        foreach( $new_ps_file_info_array as $valkey => $val )
        {
                // If the element is 'Keywords' or 'Supplemental Categories', then
                // it is an array, and needs to be treated as one
                if ( ( $valkey != 'supplementalcategories' ) && ( $valkey != 'keywords' ) )
                {
                        // Not Keywords or Supplemental Categories
                        // Convert escaped HTML characters to UTF8 and remove carriage returns
                        $new_ps_file_info_array[ $valkey ] = strtr( HTML_UTF8_UnEscape( $val ), $trans );
                }
                else
                {
                        // Either Keywords or Supplemental Categories
                        // Cycle through the array,
                        foreach( $val as $subvalkey => $subval )
                        {
                                // Convert escaped HTML characters to UTF8 and remove carriage returns
                                $new_ps_file_info_array[ $valkey ][ $subvalkey ] = strtr( HTML_UTF8_UnEscape( $subval ), $trans );
                        }
                }
        }





        /*******************************************/

        // EXIF Processing


        // Check if the EXIF array exists
        if( $Old_Exif_array == FALSE )
        {
                // EXIF Array doesn't exist - create a new one
                $new_Exif_array = array (       'Byte_Align' => "MM",
                                                'Makernote_Tag' => false,
                                                'Tags Name' => "TIFF",
                                                 0 => array( "Tags Name" => "TIFF" ) );
        }
        else
        {
                // EXIF Array Does Exist - use it
                $new_Exif_array = $Old_Exif_array;
        }



        // Update the EXIF Image Description Tag with the new value
        $new_Exif_array[0][270] = array (       "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 270 ]['Name'],
                                                "Tag Number" => 270,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 270 ]['Type'],
                                                "Data"       => array( HTML_UTF8_Escape( $new_ps_file_info_array[ 'caption' ]) ));

        // Update the EXIF Artist Name Tag with the new value
        $new_Exif_array[0][315] = array (       "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 315 ]['Name'],
                                                "Tag Number" => 315,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 315 ]['Type'],
                                                "Data"       => array( HTML_UTF8_Escape( $new_ps_file_info_array[ 'author' ] ) ) );

        // Update the EXIF Copyright Information Tag with the new value
        $new_Exif_array[0][33432] = array (     "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 33432 ]['Name'],
                                                "Tag Number" => 33432,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 33432 ]['Type'],
                                                "Data"       => array( HTML_UTF8_Escape( $new_ps_file_info_array[ 'copyrightnotice' ]) ) );


        // Photoshop checks if the "Date and Time of Original" and "Date and Time when Digitized" tags exist
        // If they don't exist, it means that the EXIF date may be wiped out if it is changed, so Photoshop
        // copies the EXIF date to these two tags

        if ( ( array_key_exists( 306, $new_Exif_array[0] ) )&&
             ( array_key_exists( 34665, $new_Exif_array[0] ) ) &&
             ( array_key_exists( 0, $new_Exif_array[0][34665] ) ) )
        {
                // Replace "Date and Time of Original" if it doesn't exist
                if ( ! array_key_exists( 36867, $new_Exif_array[0][34665][0] ) )
                {
                        $new_Exif_array[0][34665][0][36867] = array (       "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['EXIF'][ 36867 ]['Name'],
                                                "Tag Number" => 36867,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['EXIF'][ 36867 ]['Type'],
                                                "Data"       => $new_Exif_array[0][306]['Data'] );
                }

                // Replace "Date and Time when Digitized" if it doesn't exist
                if ( ! array_key_exists( 36868, $new_Exif_array[0][34665][0] ) )
                {
                        $new_Exif_array[0][34665][0][36868] = array (       "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['EXIF'][ 36868 ]['Name'],
                                                "Tag Number" => 36868,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['EXIF'][ 36868 ]['Type'],
                                                "Data"       => $new_Exif_array[0][306]['Data'] );
                }
        }


        // Photoshop changes the EXIF date Tag (306) to the current date, not the date that was entered in File Info
        $exif_date = date ( "Y:m:d H:i:s" );

        // Update the EXIF Date and Time Tag with the new value
        $new_Exif_array[0][306] = array (       "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 306 ]['Name'],
                                                "Tag Number" => 306,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 306 ]['Type'],
                                                "Data"       => array( $exif_date ) );



        // Photoshop replaces the EXIF Software or Firmware Tag with "Adobe Photoshop ..."
        // This toolkit instead preserves existing value and appends the toolkit name to the end of it

        // Check if the EXIF Software or Firmware Tag exists
        if ( array_key_exists( 305, $new_Exif_array[0] ) )
        {
                // An existing EXIF Software or Firmware Tag was found
                // Check if the existing Software or Firmware Tag already contains the Toolkit's name
                if ( stristr ( $new_Exif_array[0][305]['Data'][0], $GLOBALS[ "Software Name" ]) == FALSE )
                {
                        // Toolkit Name string not found in the existing Software/Firmware string - append it.
                        $firmware_str = $new_Exif_array[0][305]['Data'][0] . " " . $GLOBALS[ "Software Name" ];
                }
                else
                {
                        // Toolkit name already exists in Software/Firmware string - don't put another copy in the string
                        $firmware_str = $new_Exif_array[0][305]['Data'][0];
                }
        }
        else
        {
                // No Software/Firmware string exists - create one
                $firmware_str = $GLOBALS[ "Software Name" ];
        }

        // Update the EXIF Software/Firmware Tag with the new value
        $new_Exif_array[0][305] = array(        "Tag Name"   => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 305 ]['Name'],
                                                "Tag Number" => 305,
                                                "Data Type"  => 2,
                                                "Type"       => $GLOBALS[ "IFD_Tag_Definitions" ]['TIFF'][ 305 ]['Type'],
                                                "Data"       => array( HTML_UTF8_Escape( $firmware_str ) ) );





        /*******************************************/

        // Photoshop IRB Processing


        // Check if there is an existing Photoshop IRB array
        if ($Old_IRB_array == FALSE )
        {
                // No existing IRB array - create one
                $new_IRB_array = array();
        }
        else
        {
                // There is an existing Photoshop IRB array - use it
                $new_IRB_array = $Old_IRB_array;
        }

        // Remove any existing Copyright Flag, URL, or IPTC resources - these will be re-written
        foreach( $new_IRB_array as  $resno => $res )
        {
                if ( ( $res[ 'ResID' ] == 0x040A ) ||
                     ( $res[ 'ResID' ] == 0x040B ) ||
                     ( $res[ 'ResID' ] == 0x0404 ) )
                {
                        array_splice( $new_IRB_array, $resno, 1 );
                }
        }


        // Add a new Copyright Flag resource
        if ( $new_ps_file_info_array[ 'copyrightstatus' ] == "Copyrighted Work" )
        {
                $PS_copyright_flag = "\x01"; // Copyrighted
        }
        else
        {
                $PS_copyright_flag = "\x00"; // Public domain or Unmarked
        }
        $new_IRB_array[] = array(       'ResID' => 0x040A,
                                        'ResName' => $GLOBALS[ "Photoshop_ID_Names" ][0x040A],
                                        'ResDesc' => $GLOBALS[ "Photoshop_ID_Descriptions" ][0x040A],
                                        'ResEmbeddedName' => "",
                                        'ResData' => $PS_copyright_flag );



        // Add a new URL resource
        $new_IRB_array[] = array(       'ResID' => 0x040B,
                                        'ResName' => $GLOBALS[ "Photoshop_ID_Names" ][0x040B],
                                        'ResDesc' => $GLOBALS[ "Photoshop_ID_Descriptions" ][0x040B],
                                        'ResEmbeddedName' => "",
                                        'ResData' => $new_ps_file_info_array[ 'ownerurl' ] );



        // Create IPTC resource

        // IPTC requires date to be in the following format YYYYMMDD
        $iptc_date = date( "Ymd", $date_stamp );

        // Create the new IPTC array
        $new_IPTC_array = array (
                                  0 =>
                                  array (
                                    'IPTC_Type' => '2:00',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:00'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:00'],
                                    'RecData' => "\x00\x02",
                                  ),
                                  1 =>
                                  array (
                                    'IPTC_Type' => '2:120',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:120'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:120'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'caption' ] ), 0 , 2000 ),
                                  ),
                                  2 =>
                                  array (
                                    'IPTC_Type' => '2:122',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:122'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:122'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'captionwriter' ] ), 0 , 32 ),
                                  ),
                                  3 =>
                                  array (
                                    'IPTC_Type' => '2:105',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:105'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:105'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'headline' ] ), 0 , 256 ),
                                  ),
                                  4 =>
                                  array (
                                    'IPTC_Type' => '2:40',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:40'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:40'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'instructions' ] ), 0, 256 ),
                                  ),
                                  5 =>
                                  array (
                                    'IPTC_Type' => '2:80',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:80'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:80'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'author' ] ), 0, 32 ),
                                  ),
                                  6 =>
                                  array (
                                    'IPTC_Type' => '2:85',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:85'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:85'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'authorsposition' ] ), 0, 32 ),
                                  ),
                                  7 =>
                                  array (
                                    'IPTC_Type' => '2:110',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:110'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:110'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'credit' ] ), 0, 32 ),
                                  ),
                                  8 =>
                                  array (
                                    'IPTC_Type' => '2:115',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:115'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:115'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'source' ] ), 0, 32 ),
                                  ),
                                  9 =>
                                  array (
                                    'IPTC_Type' => '2:05',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:05'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:05'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'title' ] ), 0, 64 ),
                                  ),
                                  10 =>
                                  array (
                                    'IPTC_Type' => '2:55',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:55'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:55'],
                                    'RecData' => "$iptc_date",
                                  ),
                                  11 =>
                                  array (
                                    'IPTC_Type' => '2:90',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:90'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:90'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'city' ] ), 0, 32 ),
                                  ),
                                  12 =>
                                  array (
                                    'IPTC_Type' => '2:95',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:95'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:95'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'state' ] ), 0, 32 ),
                                  ),
                                  13 =>
                                  array (
                                    'IPTC_Type' => '2:101',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:101'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:101'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'country' ] ), 0, 64 ),
                                  ),
                                  14 =>
                                  array (
                                    'IPTC_Type' => '2:103',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:103'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:103'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'transmissionreference' ] ), 0, 32 ),
                                  ),
                                  15 =>
                                  array (
                                    'IPTC_Type' => '2:15',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:15'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:15'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'category' ] ), 0, 3 ),
                                  ),
                                  21 =>
                                  array (
                                    'IPTC_Type' => '2:116',
                                    'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:10'],
                                    'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:10'],
                                    'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'copyrightnotice' ] ), 0, 128 ),
                                  ),
                                );

        // Check the value of urgency is valid
        if ( ( $new_ps_file_info_array[ 'urgency' ] > 0 ) && ( $new_ps_file_info_array[ 'urgency' ] < 9 ) )
        {
                // Add the Urgency item to the IPTC array
                $new_IPTC_array[] = array (
                                                'IPTC_Type' => '2:10',
                                                'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:10'],
                                                'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:10'],
                                                'RecData' => substr( HTML_UTF8_Escape( $new_ps_file_info_array[ 'urgency' ] ), 0, 1 ),
                                          );
        }

        // Cycle through the Supplemental Categories,
        foreach( $new_ps_file_info_array[ 'supplementalcategories' ] as $supcat )
        {
                // Add this Supplemental Category to the IPTC array
                $new_IPTC_array[] = array (
                                            'IPTC_Type' => '2:20',
                                            'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:20'],
                                            'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:20'],
                                            'RecData' => HTML_UTF8_Escape( $supcat ),
                                          );
        }


        // Cycle through the Keywords,
        foreach( $new_ps_file_info_array[ 'keywords' ] as $keyword )
        {
                // Add this Keyword to the IPTC array
                $new_IPTC_array[] = array (
                                            'IPTC_Type' => '2:25',
                                            'RecName' => $GLOBALS[ "IPTC_Entry_Names" ]['2:25'],
                                            'RecDesc' => $GLOBALS[ "IPTC_Entry_Descriptions" ]['2:25'],
                                            'RecData' => $keyword,
                                          );
        }


        /***********************************/

        // XMP Processing

        // Check if XMP existed previously
        if ($Old_XMP_array == FALSE )
        {
                // XMP didn't exist - create a new one based on a blank structure
                $new_XMP_array = XMP_Check( $GLOBALS[ 'Blank XMP Structure' ], array( ) );
        }
        else
        {
                // XMP does exist
                // Some old XMP processors used x:xapmeta, check for this
                if ( $Old_XMP_array[0]['tag'] == 'x:xapmeta' )
                {
                        // x:xapmeta found - change it to x:xmpmeta
                        $Old_XMP_array[0]['tag'] = 'x:xmpmeta';
                }

                // Ensure that the existing XMP has all required fields, and add any that are missing
                $new_XMP_array = XMP_Check( $GLOBALS[ 'Blank XMP Structure' ], $Old_XMP_array );
        }


        // Process the XMP Photoshop block

        // Find the Photoshop Information within the XMP block
        $photoshop_block = & find_XMP_block( $new_XMP_array, "photoshop" );

        // The Photoshop CaptionWriter tag contains captionwriter - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:CaptionWriter" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'captionwriter' ];

        // The Photoshop Category tag contains category - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Category" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'category' ];

        // The Photoshop DateCreated tag contains date - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:DateCreated" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'date' ];

        // The Photoshop City tag contains city - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:City" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'city' ];

        // The Photoshop State tag contains state - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:State" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'state' ];

        // The Photoshop Country tag contains country - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Country" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'country' ];

        // The Photoshop AuthorsPosition tag contains authorsposition - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:AuthorsPosition" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'authorsposition' ];

        // The Photoshop Credit tag contains credit - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Credit" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'credit' ];

        // The Photoshop Source tag contains source - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Source" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'source' ];

        // The Photoshop Headline tag contains headline - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Headline" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'headline' ];

        // The Photoshop Instructions tag contains instructions - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Instructions" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'instructions' ];

        // The Photoshop TransmissionReference tag contains transmissionreference - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:TransmissionReference" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'transmissionreference' ];

        // The Photoshop Urgency tag contains urgency - Find it and Update the value
        $Item = & find_XMP_item( $photoshop_block, "photoshop:Urgency" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'urgency' ];

        // The Photoshop SupplementalCategories tag contains supplementalcategories - Find it
        $Item = & find_XMP_item( $photoshop_block, "photoshop:SupplementalCategories" );

        // Create an array to receive the XML list items for the Supplemental Categories
        $new_supcat_array = array( );

        // Cycle through the Supplemental Categories
        foreach ( $new_ps_file_info_array[ 'supplementalcategories' ] as $sup_category )
        {
                // Add a new list item for this Supplemental Category
                $new_supcat_array[] = array( 'tag' => 'rdf:li', 'value' => $sup_category );
        }

        // Add the array of Supplemental Category List Items to the Photoshop SupplementalCategories tag
        $Item[ 'children' ][ 0 ][ 'children' ] = $new_supcat_array;



        // Process the XMP XAP block

        // Find the XAP Information within the XMP block
        $XAP_block = & find_XMP_block( $new_XMP_array, "xap" );

        // The XAP CreateDate tag contains date XMP was first created - Find it and Update the value
        $Item = & find_XMP_item( $XAP_block, "xap:CreateDate" );

        // Check if the CreateDate is blank
        if ( $Item[ 'value' ] == "" )
        {
                // CreateDate is blank - we must have just added it - set it to the current date
                $Item[ 'value' ] = date( "Y-m-d\TH:i:s" );
                $Item[ 'value' ] .= get_Local_Timezone_Offset( );
        }


        // The XAP ModifyDate tag contains last resource change date  - Find it and Update the value to the current date
        $Item = & find_XMP_item( $XAP_block, "xap:ModifyDate" );
        $Item[ 'value' ] = date( "Y-m-d\TH:i:s" );
        $Item[ 'value' ] .= get_Local_Timezone_Offset( );

        // The XAP ModifyDate tag contains last XMP change date  - Find it and Update the value to the current date
        $Item = & find_XMP_item( $XAP_block, "xap:MetadataDate" );
        $Item[ 'value' ] = date( "Y-m-d\TH:i:s" );
        $Item[ 'value' ] .= get_Local_Timezone_Offset( );



        // The XAP CreatorTool tag contains name of the software editor  - Find it
        $Item = & find_XMP_item( $XAP_block, "xap:CreatorTool" );

        // Photoshop replaces the CreatorTool with "Adobe Photoshop ..."
        // This toolkit instead preserves existing value and appends the toolkit name to the end of it

        // Check if a CreatorTool already exists
        if ( $Item[ 'value' ] != "" )
        {
                // An existing CreatorTool was found
                // Check if the existing CreatorTool already contains the Toolkit's name
                if ( stristr ( $Item[ 'value' ], $GLOBALS[ "Software Name" ]) == FALSE )
                {
                        // Toolkit Name string not found in the existing CreatorTool string - append it.
                        $Item[ 'value' ] = $Item[ 'value' ] . " " . $GLOBALS[ "Software Name" ];
                }
                else
                {
                        // Toolkit name already exists in CreatorTool string - leave as is
                }
        }
        else
        {
                // No CreatorTool string exists - create one
                $Item[ 'value' ] = $GLOBALS[ "Software Name" ];
        }




        // Process the XMP Basic Job Information block

        // Find the XAP Basic Job Information within the XMP block
        $XAPBJ_block = & find_XMP_block( $new_XMP_array, "xapBJ" );

        // The XAP Basic Job JobRef tag contains urgency - Find it and Update the value
        $Item = & find_XMP_item( $XAPBJ_block, "xapBJ:JobRef" );
        $Item[ 'children' ][ 0 ][ 'children' ] =
                array( array (  'tag'        => 'rdf:li',
                                'attributes' => array ( 'rdf:parseType' => 'Resource' ),
                                'children'   => array ( 0 => array (    'tag'   => 'stJob:name',
                                                                        'value' => $new_ps_file_info_array[ 'jobname' ] ),
                                                      ),
                             ),
                     );




        // Process the XMP XAP Rights Information block

        // Find the XAP Rights Information within the XMP block
        $XAPRights_block = & find_XMP_block( $new_XMP_array, "xapRights" );



        // The XAP Rights Marked tag should only be present if copyrightstatus is 'Copyrighted Work' or 'Public Domain'
        // If copyrightstatus 'Unmarked' or anything else, the XAP Rights Marked tag should be missing


        // Remove any existing XAP Rights Marked tags - they will be replaced
        foreach( $XAPRights_block as  $tagno => $tag )
        {
                if ( $tag[ 'tag' ] == "xapRights:Marked" )
                {
                        array_splice( $XAPRights_block, $tagno, 1 );
                }
        }

        // Check the value of the copyrightstatus flag
        if ( $new_ps_file_info_array[ 'copyrightstatus' ] == "Copyrighted Work" )
        {
                // Copyrighted - add the tag
                $XAPRights_block[] = array ( 'tag' => 'xapRights:Marked', 'value' => 'True' );
        }
        else if ( $new_ps_file_info_array[ 'copyrightstatus' ] == "Public Domain" )
        {
                // Public domain - add the tag
                $XAPRights_block[] = array ( 'tag' => 'xapRights:Marked', 'value' => 'False' );
        }
        else
        {
                // Unmarked or Other - Do nothing - don't add a Marked tag
        }


        // The XAP Rights WebStatement tag contains ownerurl - Find it and Update the value
        $Item = & find_XMP_item( $XAPRights_block, "xapRights:WebStatement" );
        $Item[ 'value' ] = $new_ps_file_info_array[ 'ownerurl' ];




        // Process the XMP Dublin Core block

        // Find the Dublin Core Information within the XMP block
        $DC_block = & find_XMP_block( $new_XMP_array, "dc" );


        // The Dublin Core description tag contains caption - Find it and Update the value
        $Item = & find_XMP_item( $DC_block, "dc:description" );
        $Item[ 'children' ][ 0 ][ 'children' ] = array( array(  'tag'   => "rdf:li",
                                                                'value' => $new_ps_file_info_array[ 'caption' ],
                                                                'attributes' => array( 'xml:lang' => "x-default" ) ) );


        // The Dublin Core title tag contains title - Find it and Update the value
        $Item = & find_XMP_item( $DC_block, "dc:title" );
        $Item[ 'children' ][ 0 ][ 'children' ] = array( array(  'tag'   => "rdf:li",
                                                                'value' => $new_ps_file_info_array[ 'title' ],
                                                                'attributes' => array( 'xml:lang' => "x-default" ) ) );


        // The Dublin Core rights tag contains copyrightnotice - Find it and Update the value
        $Item = & find_XMP_item( $DC_block, "dc:rights" );
        $Item[ 'children' ][ 0 ][ 'children' ] = array( array(  'tag'   => "rdf:li",
                                                                'value' => $new_ps_file_info_array[ 'copyrightnotice' ],
                                                                'attributes' => array( 'xml:lang' => "x-default" ) ) );

        // The Dublin Core creator tag contains author - Find it and Update the value
        $Item = & find_XMP_item( $DC_block, "dc:creator" );
        $Item[ 'children' ][ 0 ][ 'children' ] = array( array(  'tag'   => "rdf:li",
                                                                'value' => $new_ps_file_info_array[ 'author' ]) );

        // The Dublin Core subject tag contains keywords - Find it
        $Item = & find_XMP_item( $DC_block, "dc:subject" );

        // Create an array to receive the Keywords List Items
        $new_keywords_array = array( );

        // Cycle through each keyword
        foreach( $new_ps_file_info_array[ 'keywords' ] as $keyword )
        {
                // Add a List item for this keyword
                $new_keywords_array[] = array(  'tag'   => "rdf:li", 'value' => $keyword );
        }
        // Add the Keywords List Items array to the Dublin Core subject tag
        $Item[ 'children' ][ 0 ][ 'children' ] = $new_keywords_array;



        /***************************************/

        // FINISHED UPDATING VALUES

        // Insert the new IPTC array into the Photoshop IRB array
        $new_IRB_array = put_Photoshop_IPTC( $new_IRB_array, $new_IPTC_array );

        // Write the EXIF array to the JPEG header
        $jpeg_header_data = put_EXIF_JPEG( $new_Exif_array, $jpeg_header_data );

        // Convert the XMP array to XMP text
        $xmp_text = write_XMP_array_to_text( $new_XMP_array );

        // Write the XMP text to the JPEG Header
        $jpeg_header_data = put_XMP_text( $jpeg_header_data, $xmp_text );

        // Write the Photoshop IRB array to the JPEG header
        $jpeg_header_data = put_Photoshop_IRB( $jpeg_header_data, $new_IRB_array );

        return $jpeg_header_data;

}

/******************************************************************************
* End of Function:     put_photoshop_file_info
******************************************************************************/



































/******************************************************************************
*
*         INTERNAL FUNCTIONS
*
******************************************************************************/


































/******************************************************************************
*
* Function:     get_Local_Timezone_Offset
*
* Description:  Returns a string indicating the time difference between the local
*               timezone and GMT in hours and minutes, e.g.  +10:00 or -06:30
*
* Parameters:   None
*
* Returns:      $tz_str - a string containing the timezone offset
*
******************************************************************************/

function get_Local_Timezone_Offset( )
{
        // Retrieve the Timezone offset in seconds
        $tz_seconds = date( "Z" );

        // Check if the offset is less than zero
        if ( $tz_seconds < 0 )
        {
                // Offset is less than zero - add a Minus sign to the output
                $tz_str = "-";
        }
        else
        {
                // Offset is greater than or equal to zero - add a Plus sign to the output
                $tz_str = "+";
        }

        // Add the absolute offset to the output, formatted as HH:MM
        $tz_str .= gmdate( "H:i", abs($tz_seconds) );

        // Return the result
        return $tz_str;
}

/******************************************************************************
* End of Function:     get_Local_Timezone_Offset
******************************************************************************/



/******************************************************************************
*
* Function:     XMP_Check
*
* Description:  Checks a given XMP array against a reference array, and adds any
*               missing blocks and tags
*
*               NOTE: This is a recursive function
*
* Parameters:   reference_array - The standard XMP array which contains all required tags
*               check_array - The XMP array to check
*
* Returns:      output - a string containing the timezone offset
*
******************************************************************************/

function XMP_Check( $reference_array, $check_array)
{
        // Cycle through each of the elements of the reference XMP array
        foreach( $reference_array as $valkey => $val )
        {

                // Search for the current reference tag within the XMP array to be checked
                $tagpos = find_XMP_Tag( $check_array,  $val );

                // Check if the tag was found
                if ( $tagpos === FALSE )
                {
                        // Tag not found - Add tag to array being checked
                        $tagpos = count( $check_array );
                        $check_array[ $tagpos ] = $val;
                }

                // Check if the reference tag has children
                if ( array_key_exists( 'children', $val ) )
                {
                        // Reference tag has children - these need to be checked too

                        // Determine if the array being checked has children for this tag
                        if ( ! array_key_exists( 'children', $check_array[ $tagpos ] ) )
                        {
                                // Array being checked has no children - add a blank children array
                                $check_array[ $tagpos ][ 'children' ] = array( );
                        }

                        // Recurse, checking the children tags against the reference children
                        $check_array[ $tagpos ][ 'children' ] = XMP_Check( $val[ 'children' ] , $check_array[ $tagpos ][ 'children' ] );
                }
                else
                {
                        // No children - don't need to check anything else
                }
        }

        // Return the checked XMP array
        return $check_array;
}


/******************************************************************************
* End of Function:     XMP_Check
******************************************************************************/




/******************************************************************************
*
* Function:     find_XMP_Tag
*
* Description:  Searches one level of an XMP array for a specific tag, and
*               returns the tag position. Does not descend the XMP tree.
*
* Parameters:   XMP_array - The XMP array which should be searched
*               tag - The XMP tag to search for (in same format as would be found in XMP array)
*
* Returns:      output - a string containing the timezone offset
*
******************************************************************************/

function find_XMP_Tag( $XMP_array, $tag )
{
        $namespacestr = "";

        // Some tags have a namespace attribute which defines them (i.e. rdf:Description tags)

        // Check if the tag being searched for has attributs
        if ( array_key_exists( 'attributes', $tag ) )
        {
                // Tag has attributes - cycle through them
                foreach( $tag['attributes'] as $key => $val )
                {
                        // Check if the current attribute is the namespace attribute - i.e. starts with xmlns:
                        if ( strcasecmp( substr($key,0,6), "xmlns:" ) == 0 )
                        {
                                // Found a namespace attribute - save it for later.
                                $namespacestr = $key;
                        }
                }
        }



        // Cycle through the elements of the XMP array to be searched.
        foreach( $XMP_array as $valkey => $val )
        {

                // Check if the current element is a rdf:Description tag
                if ( strcasecmp ( $tag[ 'tag' ], 'rdf:Description' ) == 0 )
                {
                        // Current element is a rdf:Description tag
                        // Check if the namespace attribute is the same as in the tag that is being searched for
                        if ( array_key_exists( $namespacestr, $val['attributes'] ) )
                        {
                                // Namespace is the same - this is the correct tag - return it's position
                                return $valkey;
                        }
                }
                // Otherwise check if the current element has the same name as the tag in question
                else if ( strcasecmp ( $val[ 'tag' ], $tag[ 'tag' ] ) == 0 )
                {
                        // Tags have same name - this is the correct tag - return it's position
                        return $valkey;
                }
        }

        // Cycled through all tags without finding the correct one - return error value
        return FALSE;
}

/******************************************************************************
* End of Function:     find_XMP_Tag
******************************************************************************/




/******************************************************************************
*
* Function:     create_GUID
*
* Description:  Creates a Globally Unique IDentifier, in the format that is used
*               by XMP (and Windows). This value is not guaranteed to be 100% unique,
*               but it is ridiculously unlikely that two identical values will be produced
*
* Parameters:   none
*
* Returns:      output - a string containing the timezone offset
*
******************************************************************************/

function create_GUID( )
{
        // Create a md5 sum of a random number - this is a 32 character hex string
        $raw_GUID = md5( uniqid( getmypid() . rand( ) . (double)microtime()*1000000, TRUE ) );

        // Format the string into 8-4-4-4-12 (numbers are the number of characters in each block)
        return  substr($raw_GUID,0,8) . "-" . substr($raw_GUID,8,4) . "-" . substr($raw_GUID,12,4) . "-" . substr($raw_GUID,16,4) . "-" . substr($raw_GUID,20,12);
}

/******************************************************************************
* End of Function:     create_GUID
******************************************************************************/





/******************************************************************************
*
* Function:     add_to_field
*
* Description:  Adds a value to a particular field in a Photoshop File Info array,
*               first checking whether the value is already there. If the value is
*               already in the array, it is not changed, otherwise the value is appended
*               to whatever is already in that field of the array
*
* Parameters:   field_array - The Photoshop File Info array to receive the new value
*               field - The File Info field which the value is for
*               value - The value to be written into the File Info
*               separator - The string to place between values when having to append the value
*
* Returns:      output - the Photoshop File Info array with the value added
*
******************************************************************************/

function add_to_field( $field_array, $field, $value, $separator )
{
        // Check if the value is blank
        if ( $value == "" )
        {
                // Value is blank - return File Info array unchanged
                return $field_array;
        }

        // Check if the value can be found anywhere within the existing value for this field
        if ( stristr ( $field_array[ $field ], $value ) == FALSE)
        {
                // Value could not be found
                // Check if the existing value for the field is blank
                if ( $field_array[$field] != "" )
                {
                        // Existing value for field is not blank - append a separator
                        $field_array[$field] .= $separator;
                }
                // Append the value to the field
                $field_array[$field] .= $value;
        }

        // Return the File Info Array
        return $field_array;
}

/******************************************************************************
* End of Function:     add_to_field
******************************************************************************/



/******************************************************************************
*
* Function:     find_IPTC_Resource
*
* Description:  Searches an IPTC array for a particular record, and returns it if found
*
* Parameters:   IPTC_array - The IPTC array to search
*               record_type - The IPTC record number to search for (e.g.  2:151 )
*
* Returns:      output - the contents of the record if found
*               FALSE - otherwise
*
******************************************************************************/

function find_IPTC_Resource( $IPTC_array, $record_type )
{
        // Cycle through the ITPC records
        foreach ($IPTC_array as $record)
        {
                // Check the IPTC type against the required type
                if ( $record['IPTC_Type'] == $record_type )
                {
                        // IPTC type matches - return this record
                        return $record;
                }
        }

        // No matching record found - return error code
        return FALSE;
}

/******************************************************************************
* End of Function:     find_IPTC_Resource
******************************************************************************/




/******************************************************************************
*
* Function:     find_Photoshop_IRB_Resource
*
* Description:  Searches a Photoshop IRB array for a particular resource, and returns it if found
*
* Parameters:   IRB_array - The IRB array to search
*               resource_ID - The IRB resource number to search for (e.g.  0x03F9 )
*
* Returns:      output - the contents of the resource if found
*               FALSE - otherwise
*
******************************************************************************/

function find_Photoshop_IRB_Resource( $IRB_array, $resource_ID )
{
        // Cycle through the IRB resources
        foreach( $IRB_array as $IRB_Resource )
        {
                // Check the IRB resource ID against the required ID
                if ( $resource_ID == $IRB_Resource['ResID'] )
                {
                        // Resource ID matches - return this resource
                        return $IRB_Resource;
                }
        }

        // No matching resource found - return error code
        return FALSE;
}

/******************************************************************************
* End of Function:     find_Photoshop_IRB_Resource
******************************************************************************/








/******************************************************************************
*
* Function:     find_XMP_item
*
* Description:  Searches a one level of a XMP array for a particular item by name, and returns it if found.
*               Does not descend through the XMP array
*
* Parameters:   Item_Array - The XMP array to search
*               item_name - The name of the tag to serch for (e.g.  photoshop:CaptionWriter )
*
* Returns:      output - the contents of the tag if found
*               FALSE - otherwise
*
******************************************************************************/

function & find_XMP_item( & $Item_Array, $item_name )
{
        // Cycle through the top level of the XMP array
        foreach( $Item_Array as $Item_Key => $Item )
        {
                // Check this tag name against the required tag name
                if( $Item['tag'] == $item_name )
                {
                        // The tag names match - return the item
                        return $Item_Array[ $Item_Key ];
                }
        }

        // No matching tag found - return error code
        return FALSE;
}

/******************************************************************************
* End of Function:     find_XMP_item
******************************************************************************/





/******************************************************************************
*
* Function:     find_XMP_block
*
* Description:  Searches a for a particular rdf:Description block within a XMP array, and returns its children if found.
*
* Parameters:   XMP_array - The XMP array to search as returned by read_XMP_array_from_text
*               block_name - The namespace of the XMP block to be found (e.g.  photoshop or xapRights )
*
* Returns:      output - the children of the tag if found
*               FALSE - otherwise
*
******************************************************************************/

function & find_XMP_block( & $XMP_array, $block_name )
{
        // Check that the rdf:RDF section can be found (which contains the rdf:Description tags
        if ( ( $XMP_array !== FALSE ) &&
             ( ( $XMP_array[0]['tag'] ==  "x:xapmeta" ) ||
               ( $XMP_array[0]['tag'] ==  "x:xmpmeta" ) ) &&
             ( $XMP_array[0]['children'][0]['tag'] ==  "rdf:RDF" ) )
        {
                // Found rdf:RDF
                // Make it's children easily accessible
                $RDF_Contents = $XMP_array[0]['children'][0]['children'];

                // Cycle through the children (rdf:Description tags)
                foreach ($RDF_Contents as $RDF_Key => $RDF_Item)
                {
                        // Check if this is a rdf:description tag that has children
                        if ( ( $RDF_Item['tag'] == "rdf:Description" ) &&
                             ( array_key_exists( 'children', $RDF_Item ) ) )
                        {
                                // RDF Description tag has children,
                                // Cycle through it's attributes
                                foreach( $RDF_Item['attributes'] as $key => $val )
                                {
                                        // Check if this attribute matches the namespace block name required
                                        if ( $key == "xmlns:$block_name" )
                                        {
                                                // Namespace matches required block name - return it's children
                                                return  $XMP_array[0]['children'][0]['children'][ $RDF_Key ]['children'];
                                        }
                                }
                        }
                }
        }

        // No matching rdf:Description block found
        return FALSE;
}

/******************************************************************************
* End of Function:     find_XMP_block
******************************************************************************/









/******************************************************************************
* Global Variable:      Blank XMP Structure
*
* Contents:     A template XMP array which can be used to create a new XMP segment
*
******************************************************************************/

// Create a GUID to be used in this template array
$new_GUID = create_GUID( );

$GLOBALS[ 'Blank XMP Structure' ] =
array (
  0 =>
  array (
    'tag' => 'x:xmpmeta',
    'attributes' =>
    array (
      'xmlns:x' => 'adobe:ns:meta/',
      'x:xmptk' => 'XMP toolkit 3.0-28, framework 1.6',
    ),
    'children' =>
    array (
      0 =>
      array (
        'tag' => 'rdf:RDF',
        'attributes' =>
        array (
          'xmlns:rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
          'xmlns:iX' => 'http://ns.adobe.com/iX/1.0/',
        ),
        'children' =>
        array (
          1 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:pdf' => 'http://ns.adobe.com/pdf/1.3/',
            ),
          ),
          2 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:photoshop' => 'http://ns.adobe.com/photoshop/1.0/',
            ),
            'children' =>
            array (
              0 =>
              array (
                'tag' => 'photoshop:CaptionWriter',
                'value' => '',
              ),
              1 =>
              array (
                'tag' => 'photoshop:Category',
                'value' => '',
              ),
              2 =>
              array (
                'tag' => 'photoshop:DateCreated',
                'value' => '',
              ),
              3 =>
              array (
                'tag' => 'photoshop:City',
                'value' => '',
              ),
              4 =>
              array (
                'tag' => 'photoshop:State',
                'value' => '',
              ),
              5 =>
              array (
                'tag' => 'photoshop:Country',
                'value' => '',
              ),
              6 =>
              array (
                'tag' => 'photoshop:Credit',
                'value' => '',
              ),
              7 =>
              array (
                'tag' => 'photoshop:Source',
                'value' => '',
              ),
              8 =>
              array (
                'tag' => 'photoshop:Headline',
                'value' => '',
              ),
              9 =>
              array (
                'tag' => 'photoshop:Instructions',
                'value' => '',
              ),
              10 =>
              array (
                'tag' => 'photoshop:TransmissionReference',
                'value' => '',
              ),
              11 =>
              array (
                'tag' => 'photoshop:Urgency',
                'value' => '',
              ),
              12 =>
              array (
                'tag' => 'photoshop:SupplementalCategories',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Bag',
                  ),
                ),
              ),
              13 =>
              array (
                'tag' => 'photoshop:AuthorsPosition',
                'value' => '',
              ),
            ),
          ),
          4 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:xap' => 'http://ns.adobe.com/xap/1.0/',
            ),
            'children' =>
            array (
              0 =>
              array (
                'tag' => 'xap:CreateDate',
                'value' => '',
              ),
              1 =>
              array (
                'tag' => 'xap:ModifyDate',
                'value' => '',
              ),
              2 =>
              array (
                'tag' => 'xap:MetadataDate',
                'value' => '',
              ),
              3 =>
              array (
                'tag' => 'xap:CreatorTool',
                'value' => '',
              ),
            ),
          ),
          5 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'about' => "uuid:$new_GUID",
              'xmlns:stJob' => 'http://ns.adobe.com/xap/1.0/sType/Job#',
              'xmlns:xapBJ' => 'http://ns.adobe.com/xap/1.0/bj/',
            ),
            'children' =>
            array (
              0 =>
              array (
                'tag' => 'xapBJ:JobRef',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Bag',
                    'children' =>
                    array (
                    ),
                  ),
                ),
              ),
            ),
          ),
          6 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:xapRights' => 'http://ns.adobe.com/xap/1.0/rights/',
            ),
            'children' =>
            array (
              1 =>
              array (
                'tag' => 'xapRights:WebStatement',
                'value' => '',
              ),
            ),
          ),
          7 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
            ),
            'children' =>
            array (
              0 =>
              array (
                'tag' => 'dc:format',
                'value' => 'image/jpeg',
              ),
              1 =>
              array (
                'tag' => 'dc:title',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Alt',
                  ),
                ),
              ),
              2 =>
              array (
                'tag' => 'dc:description',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Alt',
                  ),
                ),
              ),
              3 =>
              array (
                'tag' => 'dc:rights',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Alt',
                  ),
                ),
              ),
              4 =>
              array (
                'tag' => 'dc:creator',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Seq',
                  ),
                ),
              ),
              5 =>
              array (
                'tag' => 'dc:subject',
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'rdf:Bag',
                  ),
                ),
              ),
            ),
          ),

/*          0 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:exif' => 'http://ns.adobe.com/exif/1.0/',
            ),
            'children' =>
            array (

//EXIF DATA GOES HERE - Not Implemented yet
            ),
          ),
*/
/*
          2 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:tiff' => 'http://ns.adobe.com/tiff/1.0/',
            ),
            'children' =>
            array (
// TIFF DATA GOES HERE - Not Implemented yet
              0 =>
              array (
                'tag' => 'tiff:Make',
                'value' => 'NIKON CORPORATION',
              ),
            ),
          ),
*/
/*
          3 =>
          array (
            'tag' => 'rdf:Description',
            'attributes' =>
            array (
              'rdf:about' => "uuid:$new_GUID",
              'xmlns:stRef' => 'http://ns.adobe.com/xap/1.0/sType/ResourceRef#',
              'xmlns:xapMM' => 'http://ns.adobe.com/xap/1.0/mm/',
            ),
            'children' =>
            array (
// XAPMM DATA GOES HERE - Not Implemented yet
              0 =>
              array (
                'tag' => 'xapMM:DocumentID',
                'value' => 'adobe:docid:photoshop:dceba4c2-e699-11d8-94b2-b6ec48319f2d',
              ),
              1 =>
              array (
                'tag' => 'xapMM:DerivedFrom',
                'attributes' =>
                array (
                  'rdf:parseType' => 'Resource',
                ),
                'children' =>
                array (
                  0 =>
                  array (
                    'tag' => 'stRef:documentID',
                    'value' => 'adobe:docid:photoshop:5144475b-e698-11d8-94b2-b6ec48319f2d',
                  ),
                  1 =>
                  array (
                    'tag' => 'stRef:instanceID',
                    'value' => "uuid:$new_GUID",
                  ),
                ),
              ),
            ),
          ),
*/

        ),
      ),
    ),
  ),
);



/******************************************************************************
* End of Global Variable:     Blank XMP Structure
******************************************************************************/





?>