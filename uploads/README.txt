This file was downloaded from: http://www.dll-files.com



If you downloaded it from somewhere else, please let us now: http://www.dll-files.com/contact.php



Installation instructions: 


Open the zip-file you downloaded from DLL-files.com.

Extract the dll-file to a location on your computer. 
We recommend you to unzip the file to the directory of the program that is requesting the file. 

If that doesn't work, you will have to extract the file to your system directory. 
By default, this is 
C:\Windows\System (Windows 95/98/Me), 
C:\WINNT\System32 (Windows NT/2000), or 
C:\Windows\System32 (Windows XP, Vista, 7, 8).

On a 64bit version of Windows, the default folder for 32bit dll-files is C:\Windows\SysWOW64\ , and for 64bit dll-files C:\Windows\System32\ .

Make sure to overwrite any existing files (but make a backup copy of the original file). 

Reboot your computer. 


If the problem still occurs, try the following to register the dll-file:

For 32bit dll-files on a 32bit Windows, and for 64bit dll-files on a 64bit Windows:
Open an elevated command prompt. 
To do this, click Start, click All Programs, click Accessories, right-click "Command Prompt", and then click Run as administrator. 
In Windows 8, go to the Start screen. Start typing cmd and Windows will find "Command Prompt". Right click "Command Prompt" and choose "Run as administrator. 
If you are prompted for an administrator password or for a confirmation, type the password, or click Allow. 

Type regsvr32 "filename".dll and press Enter.

Registering 32bit dll-files on a 64bit windows:
Open an elevated command prompt, as instructed above.
In the command prompt, start by typing :
cd c:\windows\syswow64\
and press enter.
then type the following and press enter:
regsvr32 c:\windows\syswow64\"filename".dll


If you have any other problems, see our HELP-section at www.dll-files.com
