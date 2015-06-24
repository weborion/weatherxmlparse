import os,time,sys

def main():
	dirs = sys.argv[1:]
	for d in dirs:
		full_file_paths = []
		full_file_paths = get_files_from_dir(d)
        	print "Files under folder "+ d 
		print full_file_paths

def get_files_from_dir(directory):
	# List which will store all of the full filepaths.
	list_files = []
 	try: 	  
    	# Read directory recursively
		for root, directories, files in os.walk(directory):
			for filename in files:
        		# Join the two strings in order to form the full filepath.
        			filepath = os.path.join(root, filename)
				list_files.append(filepath)  # Add it to the list.

		return list_files
		
	except IOError:
   		print "Error: can\'t  reading directory "+ d

main()
