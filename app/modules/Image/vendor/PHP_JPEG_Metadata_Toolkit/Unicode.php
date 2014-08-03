<?php

/******************************************************************************
*
* Filename:     Unicode.php
*
* Description:  Provides functions for handling Unicode strings in PHP without
*               needing to configure the non-default mbstring extension
*
* Author:       Evan Hunter
*
* Date:         27/7/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.10
*
* Changes:      1.00 -> 1.10 : Added the following functions:
*                              smart_HTML_Entities
*                              smart_htmlspecialchars
*                              HTML_UTF16_UnEscape
*                              HTML_UTF8_UnEscape
*                              changed HTML_UTF8_Escape and HTML_UTF16_Escape to
*                              use smart_htmlspecialchars, so that characters which
*                              were already escaped would remain intact
*
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


// TODO: UTF-16 functions have not been tested fully



/******************************************************************************
*
* Unicode UTF-8 Encoding Functions
*
* Description:  UTF-8 is a Unicode encoding system in which extended characters
*               use only the upper half (128 values) of the byte range, thus it
*               allows the use of normal 7-bit ASCII text.
*               7-Bit ASCII will pass straight through UTF-8 encoding/decoding without change
*
*
* The encoding is as follows:
* Unicode Value          :  Binary representation (x=data bit)
*--------------------------------------------------------------------------------
* U-00000000 - U-0000007F:  0xxxxxxx                      <- This is 7-bit ASCII
* U-00000080 - U-000007FF:  110xxxxx 10xxxxxx
* U-00000800 - U-0000FFFF:  1110xxxx 10xxxxxx 10xxxxxx
* U-00010000 - U-001FFFFF:  11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
* U-00200000 - U-03FFFFFF:  111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
* U-04000000 - U-7FFFFFFF:  1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx
*--------------------------------------------------------------------------------
*
******************************************************************************/




/******************************************************************************
*
* Unicode UTF-16 Encoding Functions
*
* Description:  UTF-16 is a Unicode encoding system uses 16 bit values for representing
*               characters.
*               It also has an extended set of characters available by the use
*               of surrogate pairs, which are a pair of 16 bit values, giving a
*               total data length of 20 useful bits.
*
*
* The encoding is as follows:
* Unicode Value          :  Binary representation (x=data bit)
*--------------------------------------------------------------------------------
* U-000000 - U-00D7FF:  xxxxxxxx xxxxxxxx
* U-00D800 - U-00DBFF:  Not available - used for high surrogate pairs
* U-00DC00 - U-00DFFF:  Not available - used for low surrogate pairs
  U-00E000 - U-00FFFF:  xxxxxxxx xxxxxxxx
* U-010000 - U-10FFFF:  110110ww wwxxxxxx  110111xx xxxxxxxx      ( wwww = (uni-0x10000)/0x10000 )
*--------------------------------------------------------------------------------
*
*  Surrogate pair Calculations
*
*  $hi = ($uni - 0x10000) / 0x400 + 0xD800;
*  $lo = ($uni - 0x10000) % 0x400 + 0xDC00;
*
*
*  $uni = 0x10000 + ($hi - 0xD800) * 0x400 + ($lo - 0xDC00);
*
*
******************************************************************************/






/******************************************************************************
*
* Function:     UTF8_fix
*
* Description:  Checks a string for badly formed Unicode UTF-8 coding and
*               returns the same string containing only the parts which
*               were properly formed UTF-8 data.
*
* Parameters:   utf8_text - a string with possibly badly formed UTF-8 data
*
* Returns:      output - the well formed UTF-8 version of the string
*
******************************************************************************/

function UTF8_fix( $utf8_text )
{
        // Initialise the current position in the string
        $pos = 0;

        // Create a string to accept the well formed output
        $output = "" ;

        // Cycle through each group of bytes, ensuring the coding is correct
        while ( $pos < strlen( $utf8_text ) )
        {
                // Retreive the current numerical character value
                $chval = ord($utf8_text{$pos});

                // Check what the first character is - it will tell us how many bytes the
                // Unicode value covers

                if ( ( $chval >= 0x00 ) && ( $chval <= 0x7F ) )
                {
                        // 1 Byte UTF-8 Unicode (7-Bit ASCII) Character
                        $bytes = 1;
                }
                else if ( ( $chval >= 0xC0 ) && ( $chval <= 0xDF ) )
                {
                        // 2 Byte UTF-8 Unicode Character
                        $bytes = 2;
                }
                else if ( ( $chval >= 0xE0 ) && ( $chval <= 0xEF ) )
                {
                        // 3 Byte UTF-8 Unicode Character
                        $bytes = 3;
                }
                else if ( ( $chval >= 0xF0 ) && ( $chval <= 0xF7 ) )
                {
                        // 4 Byte UTF-8 Unicode Character
                        $bytes = 4;
                }
                else if ( ( $chval >= 0xF8 ) && ( $chval <= 0xFB ) )
                {
                        // 5 Byte UTF-8 Unicode Character
                        $bytes = 5;
                }
                else if ( ( $chval >= 0xFC ) && ( $chval <= 0xFD ) )
                {
                        // 6 Byte UTF-8 Unicode Character
                        $bytes = 6;
                }
                else
                {
                        // Invalid Code - skip character and do nothing
                        $bytes = 0;
                        $pos++;
                }


                // check that there is enough data remaining to read
                if (($pos + $bytes - 1) < strlen( $utf8_text ) )
                {
                        // Cycle through the number of bytes specified,
                        // copying them to the output string
                        while ( $bytes > 0 )
                        {
                                $output .= $utf8_text{$pos};
                                $pos++;
                                $bytes--;
                        }
                }
                else
                {
                        break;
                }
        }

        // Return the result
        return $output;
}

/******************************************************************************
* End of Function:     UTF8_fix
******************************************************************************/









/******************************************************************************
*
* Function:     UTF16_fix
*
* Description:  Checks a string for badly formed Unicode UTF-16 coding and
*               returns the same string containing only the parts which
*               were properly formed UTF-16 data.
*
* Parameters:   utf16_text - a string with possibly badly formed UTF-16 data
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      output - the well formed UTF-16 version of the string
*
******************************************************************************/

function UTF16_fix( $utf16_text, $MSB_first )
{
        // Initialise the current position in the string
        $pos = 0;

        // Create a string to accept the well formed output
        $output = "" ;

        // Cycle through each group of bytes, ensuring the coding is correct
        while ( $pos < strlen( $utf16_text ) )
        {
                // Retreive the current numerical character value
                $chval1 = ord($utf16_text{$pos});

                // Skip over character just read
                $pos++;

                // Check if there is another character available
                if ( $pos  < strlen( $utf16_text ) )
                {
                        // Another character is available - get it for the second half of the UTF-16 value
                        $chval2 = ord( $utf16_text{$pos} );
                }
                else
                {
                        // Error - no second byte to this UTF-16 value - end processing
                        continue 1;
                }

                // Skip over character just read
                $pos++;

                // Calculate the 16 bit unicode value
                if ( $MSB_first )
                {
                        // Big Endian
                        $UTF16_val = $chval1 * 0x100 + $chval2;
                }
                else
                {
                        // Little Endian
                        $UTF16_val = $chval2 * 0x100 + $chval1;
                }



                if ( ( ( $UTF16_val >= 0x0000 ) && ( $UTF16_val <= 0xD7FF ) ) ||
                     ( ( $UTF16_val >= 0xE000 ) && ( $UTF16_val <= 0xFFFF ) ) )
                {
                        // Normal Character (Non Surrogate pair)
                        // Add it to the output
                        $output .= chr( $chval1 ) . chr ( $chval2 );
                }
                else if ( ( $UTF16_val >= 0xD800 ) && ( $UTF16_val <= 0xDBFF ) )
                {
                        // High surrogate of a surrogate pair
                        // Now we need to read the low surrogate
                        // Check if there is another 2 characters available
                        if ( ( $pos + 3 ) < strlen( $utf16_text ) )
                        {
                                // Another 2 characters are available - get them
                                $chval3 = ord( $utf16_text{$pos} );
                                $chval4 = ord( $utf16_text{$pos+1} );

                                // Calculate the second 16 bit unicode value
                                if ( $MSB_first )
                                {
                                        // Big Endian
                                        $UTF16_val2 = $chval3 * 0x100 + $chval4;
                                }
                                else
                                {
                                        // Little Endian
                                        $UTF16_val2 = $chval4 * 0x100 + $chval3;
                                }

                                // Check that this is a low surrogate
                                if ( ( $UTF16_val2 >= 0xDC00 ) && ( $UTF16_val2 <= 0xDFFF ) )
                                {
                                        // Low surrogate found following high surrogate
                                        // Add both to the output
                                        $output .= chr( $chval1 ) . chr ( $chval2 ) . chr( $chval3 ) . chr ( $chval4 );

                                        // Skip over the low surrogate
                                        $pos += 2;
                                }
                                else
                                {
                                        // Low surrogate not found after high surrogate
                                        // Don't add either to the output
                                        // Only the High surrogate is skipped and processing continues after it
                                }

                        }
                        else
                        {
                                // Error - not enough data for low surrogate - end processing
                                continue 1;
                        }

                }
                else
                {
                        // Low surrogate of a surrogate pair
                        // This should not happen - it means this is a lone low surrogate
                        // Dont add it to the output
                }

        }

        // Return the result
        return $output;
}

/******************************************************************************
* End of Function:     UTF16_fix
******************************************************************************/





/******************************************************************************
*
* Function:     UTF8_to_unicode_array
*
* Description:  Converts a string encoded with Unicode UTF-8, to an array of
*               numbers which represent unicode character numbers
*
* Parameters:   utf8_text - a string containing the UTF-8 data
*
* Returns:      output - the array containing the unicode character numbers
*
******************************************************************************/

function UTF8_to_unicode_array( $utf8_text )
{
        // Create an array to receive the unicode character numbers output
        $output = array( );

        // Cycle through the characters in the UTF-8 string
        for ( $pos = 0; $pos < strlen( $utf8_text ); $pos++ )
        {
                // Retreive the current numerical character value
                $chval = ord($utf8_text{$pos});

                // Check what the first character is - it will tell us how many bytes the
                // Unicode value covers

                if ( ( $chval >= 0x00 ) && ( $chval <= 0x7F ) )
                {
                        // 1 Byte UTF-8 Unicode (7-Bit ASCII) Character
                        $bytes = 1;
                        $outputval = $chval;    // Since 7-bit ASCII is unaffected, the output equals the input
                }
                else if ( ( $chval >= 0xC0 ) && ( $chval <= 0xDF ) )
                {
                        // 2 Byte UTF-8 Unicode
                        $bytes = 2;
                        $outputval = $chval & 0x1F;     // The first byte is bitwise ANDed with 0x1F to remove the leading 110b
                }
                else if ( ( $chval >= 0xE0 ) && ( $chval <= 0xEF ) )
                {
                        // 3 Byte UTF-8 Unicode
                        $bytes = 3;
                        $outputval = $chval & 0x0F;     // The first byte is bitwise ANDed with 0x0F to remove the leading 1110b
                }
                else if ( ( $chval >= 0xF0 ) && ( $chval <= 0xF7 ) )
                {
                        // 4 Byte UTF-8 Unicode
                        $bytes = 4;
                        $outputval = $chval & 0x07;     // The first byte is bitwise ANDed with 0x07 to remove the leading 11110b
                }
                else if ( ( $chval >= 0xF8 ) && ( $chval <= 0xFB ) )
                {
                        // 5 Byte UTF-8 Unicode
                        $bytes = 5;
                        $outputval = $chval & 0x03;     // The first byte is bitwise ANDed with 0x03 to remove the leading 111110b
                }
                else if ( ( $chval >= 0xFC ) && ( $chval <= 0xFD ) )
                {
                        // 6 Byte UTF-8 Unicode
                        $bytes = 6;
                        $outputval = $chval & 0x01;     // The first byte is bitwise ANDed with 0x01 to remove the leading 1111110b
                }
                else
                {
                        // Invalid Code - do nothing
                        $bytes = 0;
                }

                // Check if the byte was valid
                if ( $bytes !== 0 )
                {
                        // The byte was valid

                        // Check if there is enough data left in the UTF-8 string to allow the
                        // retrieval of the remainder of this unicode character
                        if ( $pos + $bytes - 1 < strlen( $utf8_text ) )
                        {
                                // The UTF-8 string is long enough

                                // Cycle through the number of bytes required,
                                // minus the first one which has already been done
                                while ( $bytes > 1 )
                                {
                                        $pos++;
                                        $bytes--;

                                        // Each remaining byte is coded with 6 bits of data and 10b on the high
                                        // order bits. Hence we need to shift left by 6 bits (0x40) then add the
                                        // current characer after it has been bitwise ANDed with 0x3F to remove the
                                        // highest two bits.
                                        $outputval = $outputval*0x40 + ( (ord($utf8_text{$pos})) & 0x3F );
                                }

                                // Add the calculated Unicode number to the output array
                                $output[] = $outputval;
                        }
                }

        }

        // Return the resulting array
        return $output;
}

/******************************************************************************
* End of Function:     UTF8_to_unicode_array
******************************************************************************/





/******************************************************************************
*
* Function:     UTF16_to_unicode_array
*
* Description:  Converts a string encoded with Unicode UTF-16, to an array of
*               numbers which represent unicode character numbers
*
* Parameters:   utf16_text - a string containing the UTF-16 data
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      output - the array containing the unicode character numbers
*
******************************************************************************/

function UTF16_to_unicode_array( $utf16_text, $MSB_first )
{
        // Create an array to receive the unicode character numbers output
        $output = array( );


        // Initialise the current position in the string
        $pos = 0;

        // Cycle through each group of bytes, ensuring the coding is correct
        while ( $pos < strlen( $utf16_text ) )
        {
                // Retreive the current numerical character value
                $chval1 = ord($utf16_text{$pos});

                // Skip over character just read
                $pos++;

                // Check if there is another character available
                if ( $pos  < strlen( $utf16_text ) )
                {
                        // Another character is available - get it for the second half of the UTF-16 value
                        $chval2 = ord( $utf16_text{$pos} );
                }
                else
                {
                        // Error - no second byte to this UTF-16 value - end processing
                        continue 1;
                }

                // Skip over character just read
                $pos++;

                // Calculate the 16 bit unicode value
                if ( $MSB_first )
                {
                        // Big Endian
                        $UTF16_val = $chval1 * 0x100 + $chval2;
                }
                else
                {
                        // Little Endian
                        $UTF16_val = $chval2 * 0x100 + $chval1;
                }


                if ( ( ( $UTF16_val >= 0x0000 ) && ( $UTF16_val <= 0xD7FF ) ) ||
                     ( ( $UTF16_val >= 0xE000 ) && ( $UTF16_val <= 0xFFFF ) ) )
                {
                        // Normal Character (Non Surrogate pair)
                        // Add it to the output
                        $output[] = $UTF16_val;
                }
                else if ( ( $UTF16_val >= 0xD800 ) && ( $UTF16_val <= 0xDBFF ) )
                {
                        // High surrogate of a surrogate pair
                        // Now we need to read the low surrogate
                        // Check if there is another 2 characters available
                        if ( ( $pos + 3 ) < strlen( $utf16_text ) )
                        {
                                // Another 2 characters are available - get them
                                $chval3 = ord( $utf16_text{$pos} );
                                $chval4 = ord( $utf16_text{$pos+1} );

                                // Calculate the second 16 bit unicode value
                                if ( $MSB_first )
                                {
                                        // Big Endian
                                        $UTF16_val2 = $chval3 * 0x100 + $chval4;
                                }
                                else
                                {
                                        // Little Endian
                                        $UTF16_val2 = $chval4 * 0x100 + $chval3;
                                }

                                // Check that this is a low surrogate
                                if ( ( $UTF16_val2 >= 0xDC00 ) && ( $UTF16_val2 <= 0xDFFF ) )
                                {
                                        // Low surrogate found following high surrogate
                                        // Add both to the output
                                        $output[] = 0x10000 + ( ( $UTF16_val - 0xD800 ) * 0x400 ) + ( $UTF16_val2 - 0xDC00 );

                                        // Skip over the low surrogate
                                        $pos += 2;
                                }
                                else
                                {
                                        // Low surrogate not found after high surrogate
                                        // Don't add either to the output
                                        // The high surrogate is skipped and processing continued
                                }

                        }
                        else
                        {
                                // Error - not enough data for low surrogate - end processing
                                continue 1;
                        }

                }
                else
                {
                        // Low surrogate of a surrogate pair
                        // This should not happen - it means this is a lone low surrogate
                        // Don't add it to the output
                }

        }

        // Return the result
        return $output;


}

/******************************************************************************
* End of Function:     UTF16_to_unicode_array
******************************************************************************/







/******************************************************************************
*
* Function:     unicode_array_to_UTF8
*
* Description:  Converts an array of unicode character numbers to a string
*               encoded by UTF-8
*
* Parameters:   unicode_array - the array containing unicode character numbers
*
* Returns:      output - the UTF-8 encoded string representing the data
*
******************************************************************************/

function unicode_array_to_UTF8( $unicode_array )
{

        // Create a string to receive the UTF-8 output
        $output = "";

        // Cycle through each Unicode character number
        foreach( $unicode_array as $unicode_char )
        {
                // Check which range the current unicode character lies in
                if ( ( $unicode_char >= 0x00 ) && ( $unicode_char <= 0x7F ) )
                {
                        // 1 Byte UTF-8 Unicode (7-Bit ASCII) Character

                        $output .= chr($unicode_char);          // Output is equal to input for 7-bit ASCII
                }
                else if ( ( $unicode_char >= 0x80 ) && ( $unicode_char <= 0x7FF ) )
                {
                        // 2 Byte UTF-8 Unicode - binary encode data as : 110xxxxx 10xxxxxx

                        $output .= chr(0xC0 + ($unicode_char/0x40));
                        $output .= chr(0x80 + ($unicode_char & 0x3F));
                }
                else if ( ( $unicode_char >= 0x800 ) && ( $unicode_char <= 0xFFFF ) )
                {
                        // 3 Byte UTF-8 Unicode - binary encode data as : 1110xxxx 10xxxxxx 10xxxxxx

                        $output .= chr(0xE0 + ($unicode_char/0x1000));
                        $output .= chr(0x80 + (($unicode_char/0x40) & 0x3F));
                        $output .= chr(0x80 + ($unicode_char & 0x3F));
                }
                else if ( ( $unicode_char >= 0x10000 ) && ( $unicode_char <= 0x1FFFFF ) )
                {
                        // 4 Byte UTF-8 Unicode - binary encode data as : 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx

                        $output .= chr(0xF0 + ($unicode_char/0x40000));
                        $output .= chr(0x80 + (($unicode_char/0x1000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x40) & 0x3F));
                        $output .= chr(0x80 + ($unicode_char & 0x3F));
                }
                else if ( ( $unicode_char >= 0x200000 ) && ( $unicode_char <= 0x3FFFFFF ) )
                {
                        // 5 Byte UTF-8 Unicode - binary encode data as : 111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx

                        $output .= chr(0xF8 + ($unicode_char/0x1000000));
                        $output .= chr(0x80 + (($unicode_char/0x40000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x1000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x40) & 0x3F));
                        $output .= chr(0x80 + ($unicode_char & 0x3F));
                }
                else if ( ( $unicode_char >= 0x4000000 ) && ( $unicode_char <= 0x7FFFFFFF ) )
                {
                        // 6 Byte UTF-8 Unicode - binary encode data as : 1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx

                        $output .= chr(0xFC + ($unicode_char/0x40000000));
                        $output .= chr(0x80 + (($unicode_char/0x1000000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x40000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x1000) & 0x3F));
                        $output .= chr(0x80 + (($unicode_char/0x40) & 0x3F));
                        $output .= chr(0x80 + ($unicode_char & 0x3F));
                }
                else
                {
                        // Invalid Code - do nothing
                }

        }

        // Return resulting UTF-8 String
        return $output;
}

/******************************************************************************
* End of Function:     unicode_array_to_UTF8
******************************************************************************/









/******************************************************************************
*
* Function:     unicode_array_to_UTF16
*
* Description:  Converts an array of unicode character numbers to a string
*               encoded by UTF-16
*
* Parameters:   unicode_array - the array containing unicode character numbers
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      output - the UTF-16 encoded string representing the data
*
******************************************************************************/

function unicode_array_to_UTF16( $unicode_array, $MSB_first )
{

        // Create a string to receive the UTF-16 output
        $output = "";

        // Cycle through each Unicode character number
        foreach( $unicode_array as $unicode_char )
        {
                // Check which range the current unicode character lies in
                if ( ( ( $unicode_char >= 0x0000 ) && ( $unicode_char <= 0xD7FF ) ) ||
                     ( ( $unicode_char >= 0xE000 ) && ( $unicode_char <= 0xFFFF ) ) )
                {
                        // Normal 16 Bit Character  (Not a Surrogate Pair)

                        // Check what byte order should be used
                        if ( $MSB_first )
                        {
                                // Big Endian
                                $output .= chr( $unicode_char / 0x100 ) . chr( $unicode_char % 0x100 ) ;
                        }
                        else
                        {
                                // Little Endian
                                $output .= chr( $unicode_char % 0x100 ) . chr( $unicode_char / 0x100 ) ;
                        }

                }
                else if ( ( $unicode_char >= 0x10000 ) && ( $unicode_char <= 0x10FFFF ) )
                {
                        // Surrogate Pair required

                        // Calculate Surrogates
                        $High_Surrogate = ( ( $unicode_char - 0x10000 ) / 0x400 ) + 0xD800;
                        $Low_Surrogate = ( ( $unicode_char - 0x10000 ) % 0x400 ) + 0xDC00;

                        // Check what byte order should be used
                        if ( $MSB_first )
                        {
                                // Big Endian
                                $output .= chr( $High_Surrogate / 0x100 ) . chr( $High_Surrogate % 0x100 );
                                $output .= chr( $Low_Surrogate / 0x100 ) . chr( $Low_Surrogate % 0x100 );
                        }
                        else
                        {
                                // Little Endian
                                $output .= chr( $High_Surrogate % 0x100 ) . chr( $High_Surrogate / 0x100 );
                                $output .= chr( $Low_Surrogate % 0x100 ) . chr( $Low_Surrogate / 0x100 );
                        }
                }
                else
                {
                        // Invalid UTF-16 codepoint
                        // Unicode value should never be between 0xD800 and 0xDFFF
                        // Do not output this point - there is no way to encode it in UTF-16
                }

        }

        // Return resulting UTF-16 String
        return $output;
}

/******************************************************************************
* End of Function:     unicode_array_to_UTF16
******************************************************************************/





/******************************************************************************
*
* Function:     xml_UTF8_clean
*
* Description:  XML has specific requirements about the characters that are
*               allowed, and characters that must be escaped.
*               This function ensures that all characters in the given string
*               are valid, and that characters such as Quotes, Greater than,
*               Less than and Ampersand are properly escaped. Newlines and Tabs
*               are also escaped.
*               Note - Do not use this on constructed XML which includes tags,
*                      as it will escape the tags. It is designed to be used
*                      on the tag and attribute names, attribute values, and text.
*
* Parameters:   utf8_text - a string containing the UTF-8 data
*
* Returns:      output - the array containing the unicode character numbers
*
******************************************************************************/

function xml_UTF8_clean( $UTF8_text )
{
        // Ensure that the Unicode UTF8 encoding is valid.

        $UTF8_text = UTF8_fix( $UTF8_text );


        // XML only allows characters in the following unicode ranges
        // #x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF]
        // Hence we need to delete any characters that dont fit this

        // Convert the UTF-8 string to an array of unicode character numbers
        $unicode_array = UTF8_to_unicode_array( $UTF8_text );

        // Create a new array to receive the valid unicode character numbers
        $new_unicode_array = array( );

        // Cycle through the unicode character numbers
        foreach( $unicode_array as  $unichar )
        {
                // Check if the unicode character number is valid for XML
                if ( ( $unichar == 0x09 ) ||
                     ( $unichar == 0x0A ) ||
                     ( $unichar == 0x0D ) ||
                     ( ( $unichar >= 0x20 ) && ( $unichar <= 0xD7FF ) ) ||
                     ( ( $unichar >= 0xE000 ) && ( $unichar <= 0xFFFD ) ) ||
                     ( ( $unichar >= 0x10000 ) && ( $unichar <= 0x10FFFF ) ) )
                {
                       // Unicode character is valid for XML - add it to the valid characters array
                       $new_unicode_array[] = $unichar;
                }

        }

        // Convert the array of valid unicode character numbers back to UTF-8 encoded text
        $UTF8_text = unicode_array_to_UTF8( $new_unicode_array );

        // Escape any special HTML characters present
        $UTF8_text =  htmlspecialchars ( $UTF8_text, ENT_QUOTES );

        // Escape CR, LF and TAB characters, so that they are kept and not treated as expendable white space
        $trans = array( "\x09" => "&#x09;", "\x0A" => "&#x0A;", "\x0D" => "&#x0D;" );
        $UTF8_text = strtr( $UTF8_text, $trans );

        // Return the resulting XML valid string
        return $UTF8_text;
}

/******************************************************************************
* End of Function:     xml_UTF8_clean
******************************************************************************/









/******************************************************************************
*
* Function:     xml_UTF16_clean
*
* Description:  XML has specific requirements about the characters that are
*               allowed, and characters that must be escaped.
*               This function ensures that all characters in the given string
*               are valid, and that characters such as Quotes, Greater than,
*               Less than and Ampersand are properly escaped. Newlines and Tabs
*               are also escaped.
*               Note - Do not use this on constructed XML which includes tags,
*                      as it will escape the tags. It is designed to be used
*                      on the tag and attribute names, attribute values, and text.
*
* Parameters:   utf16_text - a string containing the UTF-16 data
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      output - the array containing the unicode character numbers
*
******************************************************************************/

function xml_UTF16_clean( $UTF16_text, $MSB_first )
{
        // Ensure that the Unicode UTF16 encoding is valid.

        $UTF16_text = UTF16_fix( $UTF16_text, $MSB_first );


        // XML only allows characters in the following unicode ranges
        // #x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF]
        // Hence we need to delete any characters that dont fit this

        // Convert the UTF-16 string to an array of unicode character numbers
        $unicode_array = UTF16_to_unicode_array( $UTF16_text, $MSB_first );

        // Create a new array to receive the valid unicode character numbers
        $new_unicode_array = array( );

        // Cycle through the unicode character numbers
        foreach( $unicode_array as  $unichar )
        {
                // Check if the unicode character number is valid for XML
                if ( ( $unichar == 0x09 ) ||
                     ( $unichar == 0x0A ) ||
                     ( $unichar == 0x0D ) ||
                     ( ( $unichar >= 0x20 ) && ( $unichar <= 0xD7FF ) ) ||
                     ( ( $unichar >= 0xE000 ) && ( $unichar <= 0xFFFD ) ) ||
                     ( ( $unichar >= 0x10000 ) && ( $unichar <= 0x10FFFF ) ) )
                {
                       // Unicode character is valid for XML - add it to the valid characters array
                       $new_unicode_array[] = $unichar;
                }

        }

        // Convert the array of valid unicode character numbers back to UTF-16 encoded text
        $UTF16_text = unicode_array_to_UTF16( $new_unicode_array, $MSB_first );

        // Escape any special HTML characters present
        $UTF16_text =  htmlspecialchars ( $UTF16_text, ENT_QUOTES );

        // Escape CR, LF and TAB characters, so that they are kept and not treated as expendable white space
        $trans = array( "\x09" => "&#x09;", "\x0A" => "&#x0A;", "\x0D" => "&#x0D;" );
        $UTF16_text = strtr( $UTF16_text, $trans );

        // Return the resulting XML valid string
        return $UTF16_text;
}

/******************************************************************************
* End of Function:     xml_UTF16_clean
******************************************************************************/






/******************************************************************************
*
* Function:     HTML_UTF8_Escape
*
* Description:  A HTML page can display UTF-8 data properly if it has a
*               META http-equiv="Content-Type" tag with the content attribute
*               including the value: "charset=utf-8".
*               Otherwise the ISO-8859-1 character set is usually assumed, and
*               Unicode values above 0x7F must be escaped.
*               This function takes a UTF-8 encoded string and escapes the
*               characters above 0x7F as well as reserved HTML characters such
*               as Quotes, Greater than, Less than and Ampersand.
*
* Parameters:   utf8_text - a string containing the UTF-8 data
*
* Returns:      htmloutput - a string containing the HTML equivalent
*
******************************************************************************/

function HTML_UTF8_Escape( $UTF8_text )
{

        // Ensure that the Unicode UTF8 encoding is valid.
        $UTF8_text = UTF8_fix( $UTF8_text );

        // Change: changed to use smart_htmlspecialchars, so that characters which were already escaped would remain intact, as of revision 1.10
        // Escape any special HTML characters present
        $UTF8_text =  smart_htmlspecialchars( $UTF8_text, ENT_QUOTES );

        // Convert the UTF-8 string to an array of unicode character numbers
        $unicode_array = UTF8_to_unicode_array( $UTF8_text );

        // Create a string to receive the escaped HTML
        $htmloutput = "";

        // Cycle through the unicode character numbers
        foreach( $unicode_array as  $unichar )
        {
                // Check if the character needs to be escaped
                if ( ( $unichar >= 0x00 ) && ( $unichar <= 0x7F ) )
                {
                        // Character is less than 0x7F - add it to the html as is
                        $htmloutput .= chr( $unichar );
                }
                else
                {
                        // Character is greater than 0x7F - escape it and add it to the html
                        $htmloutput .= "&#x" . dechex($unichar) . ";";
                }
        }

        // Return the resulting escaped HTML
        return $htmloutput;
}

/******************************************************************************
* End of Function:     HTML_UTF8_Escape
******************************************************************************/



/******************************************************************************
*
* Function:     HTML_UTF8_UnEscape
*
* Description:  Converts HTML which contains escaped decimal or hex characters
*               into UTF-8 text
*
* Parameters:   HTML_text - a string containing the HTML text to convert
*
* Returns:      utfoutput - a string containing the UTF-8 equivalent
*
******************************************************************************/

function HTML_UTF8_UnEscape( $HTML_text )
{
        preg_match_all( "/\&\#(\d+);/", $HTML_text, $matches);
        preg_match_all( "/\&\#[x|X]([A|B|C|D|E|F|a|b|c|d|e|f|0-9]+);/", $HTML_text, $hexmatches);
        foreach( $hexmatches[1] as $index => $match )
        {
                $matches[0][] = $hexmatches[0][$index];
                $matches[1][] = hexdec( $match );
        }

        for ( $i = 0; $i < count( $matches[ 0 ] ); $i++ )
        {
                $trans = array( $matches[0][$i] => unicode_array_to_UTF8( array( $matches[1][$i] ) ) );

                $HTML_text = strtr( $HTML_text , $trans );
        }
        return $HTML_text;
}

/******************************************************************************
* End of Function:     HTML_UTF8_UnEscape
******************************************************************************/






/******************************************************************************
*
* Function:     HTML_UTF16_Escape
*
* Description:  A HTML page can display UTF-16 data properly if it has a
*               META http-equiv="Content-Type" tag with the content attribute
*               including the value: "charset=utf-16".
*               Otherwise the ISO-8859-1 character set is usually assumed, and
*               Unicode values above 0x7F must be escaped.
*               This function takes a UTF-16 encoded string and escapes the
*               characters above 0x7F as well as reserved HTML characters such
*               as Quotes, Greater than, Less than and Ampersand.
*
* Parameters:   utf16_text - a string containing the UTF-16 data
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      htmloutput - a string containing the HTML equivalent
*
******************************************************************************/

function HTML_UTF16_Escape( $UTF16_text, $MSB_first )
{

        // Ensure that the Unicode UTF16 encoding is valid.
        $UTF16_text = UTF16_fix( $UTF16_text, $MSB_first );

        // Change: changed to use smart_htmlspecialchars, so that characters which were already escaped would remain intact, as of revision 1.10
        // Escape any special HTML characters present
        $UTF16_text =  smart_htmlspecialchars( $UTF16_text );

        // Convert the UTF-16 string to an array of unicode character numbers
        $unicode_array = UTF16_to_unicode_array( $UTF16_text, $MSB_first );

        // Create a string to receive the escaped HTML
        $htmloutput = "";

        // Cycle through the unicode character numbers
        foreach( $unicode_array as  $unichar )
        {
                // Check if the character needs to be escaped
                if ( ( $unichar >= 0x00 ) && ( $unichar <= 0x7F ) )
                {
                        // Character is less than 0x7F - add it to the html as is
                        $htmloutput .= chr( $unichar );
                }
                else
                {
                        // Character is greater than 0x7F - escape it and add it to the html
                        $htmloutput .= "&#x" . dechex($unichar) . ";";
                }
        }

        // Return the resulting escaped HTML
        return $htmloutput;
}

/******************************************************************************
* End of Function:     HTML_UTF16_Escape
******************************************************************************/


/******************************************************************************
*
* Function:     HTML_UTF16_UnEscape
*
* Description:  Converts HTML which contains escaped decimal or hex characters
*               into UTF-16 text
*
* Parameters:   HTML_text - a string containing the HTML text to be converted
*               MSB_first - True will cause processing as Big Endian UTF-16 (Motorola, MSB first)
*                           False will cause processing as Little Endian UTF-16 (Intel, LSB first)
*
* Returns:      utfoutput - a string containing the UTF-16 equivalent
*
******************************************************************************/

function HTML_UTF16_UnEscape( $HTML_text, $MSB_first )
{
        $utf8_text = HTML_UTF8_UnEscape( $HTML_text );

        return unicode_array_to_UTF16( UTF8_to_unicode_array( $utf8_text ), $MSB_first );
}

/******************************************************************************
* End of Function:     HTML_UTF16_UnEscape
******************************************************************************/




/******************************************************************************
*
* Function:     smart_HTML_Entities
*
* Description:  Performs the same function as HTML_Entities, but leaves entities
*               that are already escaped intact.
*
* Parameters:   HTML_text - a string containing the HTML text to be escaped
*
* Returns:      HTML_text_out - a string containing the escaped HTML text
*
******************************************************************************/

function smart_HTML_Entities( $HTML_text )
{
        // Get a table containing the HTML entities translations
        $translation_table = get_html_translation_table( HTML_ENTITIES );

        // Change the ampersand to translate to itself, to avoid getting &amp;
        $translation_table[ chr(38) ] = '&';

        // Perform replacements
        // Regular expression says: find an ampersand, check the text after it,
        // if the text after it is not one of the following, then replace the ampersand
        // with &amp;
        // a) any combination of up to 4 letters (upper or lower case) with at least 2 or 3 non whitespace characters, then a semicolon
        // b) a hash symbol, then between 2 and 7 digits
        // c) a hash symbol, an 'x' character, then between 2 and 7 digits
        // d) a hash symbol, an 'X' character, then between 2 and 7 digits
        return preg_replace( "/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,7}|#x[0-9]{2,7}|#X[0-9]{2,7};)/","&amp;" , strtr( $HTML_text, $translation_table ) );
}

/******************************************************************************
* End of Function:     smart_HTML_Entities
******************************************************************************/



/******************************************************************************
*
* Function:     smart_htmlspecialchars
*
* Description:  Performs the same function as htmlspecialchars, but leaves characters
*               that are already escaped intact.
*
* Parameters:   HTML_text - a string containing the HTML text to be escaped
*
* Returns:      HTML_text_out - a string containing the escaped HTML text
*
******************************************************************************/

function smart_htmlspecialchars( $HTML_text )
{
        // Get a table containing the HTML special characters translations
        $translation_table=get_html_translation_table (HTML_SPECIALCHARS);

        // Change the ampersand to translate to itself, to avoid getting &amp;
        $translation_table[ chr(38) ] = '&';

        // Perform replacements
        // Regular expression says: find an ampersand, check the text after it,
        // if the text after it is not one of the following, then replace the ampersand
        // with &amp;
        // a) any combination of up to 4 letters (upper or lower case) with at least 2 or 3 non whitespace characters, then a semicolon
        // b) a hash symbol, then between 2 and 7 digits
        // c) a hash symbol, an 'x' character, then between 2 and 7 digits
        // d) a hash symbol, an 'X' character, then between 2 and 7 digits
        return preg_replace( "/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,7}|#x[0-9]{2,7}|#X[0-9]{2,7};)/","&amp;" , strtr( $HTML_text, $translation_table ) );
}

/******************************************************************************
* End of Function:     smart_htmlspecialchars
******************************************************************************/


?>