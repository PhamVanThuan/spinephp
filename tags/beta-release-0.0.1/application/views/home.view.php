<p>
	Hello and welcome to Spine PHP. This is an example view page, with no logic in it. What is a View? Well a View is the <strong>V</strong> in MVC.
	Views are able to contain logic relevant to the end-user display, or what the user will end up seeing.
</p>

<p>
	For example, let's say a user builds an array of vegetables in their controller and they want to display that array as a list in their
	View. Because the purpose of an MVC is to separate the logic, we don't want to be throwing HTML around in our controller. So, we <strong>set</strong>
	a variable to use in the view from our controller.
</p>

<highlight lang="php" lines="false">
$vegetables = array('Pumpkin','Potato','Carrot','Peas');
$this->View->set('veggies', $vegetables);

// Write the index view to the content template variable.
$this->write_view('content', 'index');
</highlight>

<p>
	What that code does is creates an array of vegetables, then we set a variable called <em>veggies</em> to that array.
	Then it sets the content to the index view file. Here is what index.view.php might look like:
</p>

<highlight lines="false">
I can grow the following vegetables:
<ul>
&lt;?php
	foreach($veggies as $v){
		echo "<li>" . $v . "</li>";
	}
?&gt;
</ul>
</highlight>

<p>
	The above view file handles the formatted array and would display something like this:
</p>

<highlight lines="false" lang="text">
I can grow the following vegetables:

	1. Pumpkin
	2. Potato
	3. Carrot
	4. Peas
</highlight>