<?php
namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $oral=Category::updateOrCreate(['slug'=>'cuidado-oral'],['name'=>'Cuidado oral','description'=>'Productos para la rutina diaria de higiene bucal.','sort_order'=>1]);
  $hair=Category::updateOrCreate(['slug'=>'cabello'],['name'=>'Cabello','description'=>'Cuidado y limpieza capilar.','sort_order'=>2]);
  $body=Category::updateOrCreate(['slug'=>'cuidado-corporal'],['name'=>'Cuidado corporal','description'=>'Productos para higiene y cuidado cotidiano.','sort_order'=>3]);
  $items=[
   [$oral,'1148','pasta-dental-menta-fresca-xylitol','Pasta Dental Menta Fresca y Xylitol',33000,'120 g',28,'Pasta dental HERBS desarrollada a base de elementos naturales y tradicionales chinos para la limpieza y el cuidado de la salud oral.','Cepille sus dientes después de cada comida, al menos tres veces al día o como recomiende su odontólogo.',['Producto libre de flúor','Libre de triclosán','Frescura a menta']],
   [$oral,'1197','pasta-dental-probiotica','Pasta Dental Probiótica',30000,'120 g',29,'Producto cosmético dentífrico diseñado para la limpieza y el cuidado integral de la salud bucal.',null,['Ayuda a remover la placa y manchas superficiales','Libre de triclosán']],
   [$hair,'1177','shampoo-de-keratina','Shampoo de Keratina',72000,'255 ml',32,'Shampoo fortificado con queratina hidrolizada y una combinación de plantas para el cuidado del cabello.',null,['Protege el cuero cabelludo','La queratina hidrolizada ayuda a dejar el cabello flexible y liso']],
   [$body,'1179','gel-de-bano-de-turmalina','Gel de Baño de Turmalina',54000,'255 ml',32,'Gel de baño de turmalina para la limpieza corporal.',null,['Deja la piel limpia, tersa y fresca']],
   [$body,'1199','deodorant-man','Deodorant Man',96000,'60 ml',33,'Fórmula suave con extractos naturales para el cuidado y protección de la piel en hombres.','Agite el frasco y aplique sobre la piel limpia y seca, siguiendo las indicaciones del catálogo.',['Neutraliza y previene los olores desagradables','Brinda sensación de frescura','Hidrata la piel']],
   [$body,'1200','deodorant-woman','Deodorant Woman',96000,'60 ml',33,'Desodorante corporal con fórmula suave para el cuidado cotidiano de la piel.','Agite el frasco y aplique sobre la piel limpia y seca, siguiendo las indicaciones del catálogo.',['Neutraliza y previene los olores desagradables','Brinda sensación de frescura','Deja un ligero perfume']],
  ];
  foreach($items as [$category,$code,$slug,$name,$price,$content,$page,$description,$usage,$benefits]) Product::updateOrCreate(['code'=>$code],[
   'category_id'=>$category->id,'slug'=>$slug,'name'=>$name,'brand'=>'HGW','description'=>$description,'short_description'=>$description,
   'price'=>$price,'currency'=>'COP','net_content'=>$content,'usage'=>$usage,'catalog_benefits'=>$benefits,'source_page'=>$page,
   'availability'=>'confirm','featured'=>in_array($code,['1148','1177','1200'],true),
  ]);
  foreach([
   '1148'=>'products/1148-pasta-dental.webp','1197'=>'products/1197-pasta-dental-probiotica.webp',
   '1177'=>'products/1177-shampoo-keratina.webp','1179'=>'products/1179-gel-bano-turmalina.webp',
   '1199'=>'products/1199-deodorant-man.webp','1200'=>'products/1200-deodorant-woman.webp',
  ] as $code=>$image) Product::where('code',$code)->update(['image'=>$image]);

  $extraCategories=[];
  foreach(['Alimentos y bebidas','Cuidado facial','Higiene femenina','Accesorios','Equipos y hogar','Cuidado personal'] as $order=>$name){
   $slug=\Illuminate\Support\Str::slug($name);
   $extraCategories[$name]=Category::updateOrCreate(['slug'=>$slug],['name'=>$name,'sort_order'=>$order+4]);
  }
  $additional=[
   ['0134','Berry Coffee',96000,12,'Alimentos y bebidas'],['0133','Ganoderma Soluble Coffee',96000,13,'Alimentos y bebidas'],
   [null,'Berry Gano Coffee',84000,14,'Alimentos y bebidas'],[null,'Black Tea Coffee',84000,15,'Alimentos y bebidas'],
   [null,'Lactiberry Probiótico y Té Negro',108000,16,'Alimentos y bebidas'],[null,'Blueberry Fruit',48000,17,'Alimentos y bebidas'],
   [null,'Caramelos de Arándano',24000,18,'Alimentos y bebidas'],[null,'Blueberry Liquid Candy',24000,19,'Alimentos y bebidas'],
   [null,'Ganoderma Liquid Candy',34800,19,'Alimentos y bebidas'],[null,'Black Tea Candy',24000,20,'Alimentos y bebidas'],
   [null,'Mint Candy',24000,20,'Alimentos y bebidas'],[null,'Proteína de Soya y Arándano',150800,21,'Alimentos y bebidas'],
   ['0253','Berry Juice High VC',102000,22,'Alimentos y bebidas'],[null,'Galletas de Arándanos, Semillas de Ajonjolí y Linaza',90000,23,'Alimentos y bebidas'],
   [null,'Choco Blue',168000,24,'Alimentos y bebidas'],[null,'Choco Gano',168000,24,'Alimentos y bebidas'],
   ['0251','Blueberry Juice',234000,25,'Alimentos y bebidas'],['135','Blueberry Concentrate',260000,26,'Alimentos y bebidas'],
   ['1209-1','Pasta Dental de Turmalina Blanca',30000,30,'Cuidado personal'],['1209-2','Pasta Dental de Turmalina Negra',30000,30,'Cuidado personal'],
   ['1140','Jabón Revitalizante de Turmalina',19800,31,'Cuidado personal'],['1145','Jabón Revitalizante de Oliva',19800,31,'Cuidado personal'],
   [null,'Aloe Vera Gel',24000,34,'Cuidado personal'],[null,'Protector Solar Blanqueador HGW SPF 50',84000,35,'Cuidado personal'],
   ['2034','Plantilla de Turmalina',36000,36,'Cuidado personal'],['1121','Toalla Higiénica Uso Diario',23100,38,'Higiene femenina'],
   ['1122','Toalla Higiénica Nocturna',23100,38,'Higiene femenina'],['1123','Protectores Diarios',21120,38,'Higiene femenina'],
   [null,'Tratamiento Facial Completo de Arándanos',null,39,'Cuidado facial'],['1174','Limpiador Facial con Arándanos',66000,40,'Cuidado facial'],
   ['1172','Tonificador Facial con Arándanos',126000,41,'Cuidado facial'],['1171','Crema de Contorno de Ojos con Arándanos',144000,42,'Cuidado facial'],
   ['1152','Esencia Facial con Arándanos',204000,43,'Cuidado facial'],['1173','Loción Humectante Facial de Arándanos',162000,44,'Cuidado facial'],
   ['1170','Crema Humectante Facial de Arándanos',126000,45,'Cuidado facial'],['1154','Mascarilla Facial',96000,46,'Cuidado facial'],
   ['1175','Crema Humectante para Manos',42000,46,'Cuidado personal'],[null,'Kit de Uñas Postizas Naturales',57000,48,'Accesorios'],
   [null,'Kit de Uñas Postizas Francesas',36000,48,'Accesorios'],['1180','Mingdeshi Jia Soothing Essence Gel',156000,49,'Cuidado personal'],
   ['2129','Collar de Turmalina',168000,50,'Accesorios'],[null,'Collar de Turmalina Premium',180000,50,'Accesorios'],
   ['2130','Pulsera de Turmalina',84000,51,'Accesorios'],[null,'Pulsera de Turmalina Premium',90000,51,'Accesorios'],
   ['1186','Smart Watch',396000,52,'Equipos y hogar'],['2165','Protector de Cintura Auto-calentable',288000,53,'Equipos y hogar'],
   ['2167','Protector de Cuello Auto-calentable',66000,54,'Equipos y hogar'],['2192','Almohada Tourmaline Magnet',372000,55,'Equipos y hogar'],
   ['2149','Colgante de Piedra Energética',210000,56,'Accesorios'],['2135','Desinfectante Portátil',390000,58,'Equipos y hogar'],
   ['2113','Thermo de Turmalina',390000,59,'Equipos y hogar'],['2151','Generador de Ozono HGW',540000,60,'Equipos y hogar'],
   ['1176','Lavavajillas Clean Towel',null,61,'Equipos y hogar'],['2146','Masajeador Eléctrico para Pies con Infrarrojo Lejano',null,62,'Equipos y hogar'],
   ['1214','Hervidor Eléctrico',252000,63,'Equipos y hogar'],['1180-CUP','Coffee Cup HGW',108000,64,'Equipos y hogar'],
   ['1213','Vaso Térmico',132000,65,'Equipos y hogar'],['3095','Gel Pen',84000,66,'Accesorios'],[null,'Cufflinks HGW',46200,67,'Accesorios'],
  ];
  foreach($additional as [$code,$name,$price,$page,$categoryName]){
   $slug=\Illuminate\Support\Str::slug($name);
   Product::updateOrCreate(['slug'=>$slug],[
    'category_id'=>$extraCategories[$categoryName]->id,'code'=>$code,'name'=>$name,'brand'=>'HGW','price'=>$price,'currency'=>'COP',
    'short_description'=>'Producto incluido en el catálogo HGW 2026.','source_page'=>$page,'availability'=>'confirm','featured'=>false,
   ]);
  }

  Product::whereNull('image')->each(function (Product $product): void {
   $image = 'products/'.$product->slug.'.webp';
   if (is_file(storage_path('app/public/'.$image))) {
    $product->update(['image'=>$image]);
   }
  });
 }
}
