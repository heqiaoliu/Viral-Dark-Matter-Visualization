import java.util.*;
import java.io.*;

class Parser{
	public static void main(String args[]) throws Exception {
		Scanner in=new Scanner(System.in);
		String filein=in.nextLine();
		in.close();
		File infile=new File(filein);
		Scanner inf=new Scanner(infile);
		String curOut=null;
		PrintWriter out=null;
		int i=0;
		while(inf.hasNext()){
			String line=inf.nextLine();
			String[] cols=line.split(",");
			if(curOut==null){
				i=0;
				curOut=cols[0];
				out=new PrintWriter(new FileWriter(curOut));
			}
			if(curOut!=null&&(!curOut.equals(cols[0]))){
				i=0;
				curOut=cols[0];
				out.close();
				out=new PrintWriter(new FileWriter(curOut));
			}
			switch(cols[5]){
				case "Class A":
					out.println(++i+","+1);
					break;
				case "Class B":
					out.println(++i+","+2);
					break;
				case "Class C":
					out.println(++i+","+3);
					break;
				case "Class D":
					out.println(++i+","+4);
					break;
			}
		}
		out.close();
	}

	
}
