
function getAvg(arrs,k){
	var temp=0;
	for(var x in arrs)
		temp+=arrs[x][k];
	return Math.round(temp/arrs.length*100000)/100000;
}

function getLambda(arrs,avg,k){
	var temp=0;
	for (var x in arrs)
		temp+=Math.pow(arrs[x][k]-avg,2);
	return	Math.sqrt(temp/arrs.length);
}
function getMedian(arrs,k){
	var temp=[];
	for(var x in arrs)
		temp.push(arrs[x][k]);
	temp.sort();
	if(temp.length%2==1)
		return temp[Math.floor(temp.length/2)];
	else
		return (temp[temp.length/2]+temp[temp.length/2-1])/2;
}

function StatisticalObject(len){
	this.avg={};
	this.lambda={};
	this.median={};
	this.dataset=[];
	this.length=len;
}

StatisticalObject.prototype.pushArr=function(arr){
	this.dataset.push(arr);
}

StatisticalObject.prototype.getAll=function(){
	this.getAvg();
	this.getLambda();
	this.getMedian();
}

StatisticalObject.prototype.getAvg=function(){
	for(var x in this.dataset[0]){
		this.avg[x]=getAvg(this.dataset,x);
	}
}

StatisticalObject.prototype.getLambda=function(){
	for(var x in this.avg)
		this.lambda[x]=getLambda(this.dataset,this.avg[x],x);	
}

StatisticalObject.prototype.getMedian=function(){
	for(var x in this.dataset[0])
		this.median[x]=getMedian(this.dataset,x);
}

StatisticalObject.prototype.test=function(){
	console.log(this);
}

StatisticalObject.prototype.getTwoDAvg=function(){
	var temp=[];
	for(var x in this.avg)
		temp.push([parseFloat(x),this.avg[x]]);
	return temp;
}

StatisticalObject.prototype.getTwoDMedian=function(){
	var temp=[];
	for(var x in this.median)
		temp.push([parseFloat(x),this.median[x]]);
	return temp;
}
StatisticalObject.prototype.getTwoDError=function(){
	var temp=[];
	for(var x in this.avg)
		temp.push([parseFloat(x),this.avg[x]+this.lambda[x],this.avg[x]-this.lambda[x]]);
	return temp;
}
