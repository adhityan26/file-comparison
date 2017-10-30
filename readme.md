# File Comparison

This project purpose is to scan recurrence file found on a folder and then display the file content als the number of recurrence found.

## How to use
To run this application simply call main.php 
        
    php main.php [-p=path] [-d=deep] [-e=ext] [-r=preview]

#### Parameter:
* -p: Directory path that will be scanned (default: "." -> current directory).
* -d: Directory deepness to be scanned (default: -1 -> infinite as long there are directory present).
* -e: Extension file that will be scanned (default: * -> all extension file), it use regex so "jp*" will scan all file with extension starting with jp. Ex: jpeg, jpg.
* -r: Preview character when displaying total number of occurrence as inline string (default: 50), if the value is less than 1, it will display all the content.

Example:

    php main.php -p="D:\Adit\DropsuiteTest\DropsuiteTest\scan" -d=-1 -e=* -r=50
    
will result output:

    Scanning directory: D:\Adit\DropsuiteTest\DropsuiteTest\scan
    Scan extension file: *
    Directory deep: Infinite
    Start 2017/10/30 10:40:19
    =========================================================================
    ------------------------------Count Content------------------------------
       Bud1                               : 1
    
    abcdef: 4
    
       Bud1                                 : 1
    
    abcdefghijkl: 1
    
    --------------------------------End Count--------------------------------
    =========================================================================
    
    =========================================================================
    -------------------------Highest Recurrent File--------------------------
    abcdef: 4
    ----------------------------------End------------------------------------
    =========================================================================
    
    End 2017/10/30 10:40:19
    Total file processed: 7
    Time elapsed: 0.10805

Block "**Count Content**" is list unique file content with it's recurrence number. Block "**Highest Recurrent File**" display the highest recurrence file content.

### Result
Result with 6.190 files, 90 folders, and total of 6.17GB size it took 273,24 second to finished.

![N|Solid](http://image.ibb.co/c7zTbR/image.png)
![N|Solid](http://image.ibb.co/mwh9i6/image.png)