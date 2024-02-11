//compress
export function compressByImgCur(imgCur){
  let compressedImg = imgCur.split('').reduce((o, c) => {
   if (o[o.length - 2] === c && o[o.length - 1] < 35) o[o.length - 1]++;
   else o.push(c, 0);
   return o;
 },[]).map(_ => typeof _ === 'number' ? _.toString(36) : _).join('');
 return compressedImg;  
} 

//decompresss

export function decompressByCompressedImage(compressedImgCur)
{
  let decompressedImgCur = compressedImgCur
   .split('').map((c,i,a)=>i%2?undefined:new Array(2+parseInt(a[i+1],36)).join(c)).join('');
}