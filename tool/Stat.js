function basicStat(){
	this.sum=[];
	this.buffer=[];
	this.upper=[];
	this.down=[];
	this.avg=[]
	this.count=0;
	this.doneAvg=false;
}
    
basicStat.prototype.addArr=function(arr,d){
	this.buffer.push(arr);
	console.log(arr);
	if(++this.count==1)
		this.sum=arr;
	else if(this.sum.length!=arr.length){
		console.log("Error: array length not match for basicStat.");
		--count;
	}
	else{
		for(var i=0;i<this.sum.length;i++)
			this.sum[i][d]+=arr[i][d];
	}

}

basicStat.prototype.getAvg=function(d){
	for(var i=0;i<this.sum.length;i++){
		this.avg.push(new Array());
		for(var x in this.sum[i]){
		if(x!=d)
			this.avg[i].push(this.sum[i][x]);
		else 
			this.avg[i].push(this.sum[i][x]/this.count);
		}
	}
	return this.avg;
}

basicStat.prototype.getDev=function(d){
	if(!this.doneAvg)
		this.getAvg(d);
	for(var i=0;i<this.sum.length;i++){
		var error=new Array();
		for(x in this.buffer){
			for(y in this.buffer[x][i]){
				if(x==0){
					if(y!=d)
						error.push(this.buffer[x][i][y]);
					else
						error.push(Math.pow(this.buffer[x][i][y]-this.avg[i][y],2));
				}
				else if(y==d)
						error[y]+=Math.pow(this.buffer[x][i][y]-this.avg[i][y],2);
					
			}
		}
		error[d]=Math.sqrt(error[d]/this.count);
		this.upper.push(new Array());
		this.down.push(new Array());
		for(x in this.avg[i]){
			if(x==d){
				this.upper[i].push(this.avg[i][x]+error[x]);
				this.down[i].push(this.avg[i][x]-error[x]);
			}
			else{
				this.upper[i].push(this.avg[i][x]);
				this.down[i].push(this.avg[i][x]);
			}
				
		}
			
	}	
	
}
