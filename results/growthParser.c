#include <stdio.h>
#include <stdlib.h>

main(int argv,char** argvs){
	if(argv!=3)
		exit(0);
	else{
		FILE *fp=fopen(argvs[1],"r");
		FILE *to=fopen(argvs[2],"w");
		char c;
		while((c=fgetc(fp))!=EOF){
			if(c=='	')
				fputc(',',to);
			else if(c==',')
				fputc(':',to);
			else
				fputc(c,to);
		}
		fclose(fp);		
		fclose(to);		
	}
}
