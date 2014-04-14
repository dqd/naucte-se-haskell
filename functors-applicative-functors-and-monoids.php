<h1><?=$contents['functors-applicative-functors-and-monoids']['title']?></h1>
<p>Haskell's combination of purity, higher order functions, parameterized algebraic data types, and typeclasses allows us to implement polymorphism on a much higher level than possible in other languages. We don't have to think about types belonging to a big hierarchy of types. Instead, we think about what the types can act like and then connect them with the appropriate typeclasses. An <span class="fixed">Int</span> can act like a lot of things. It can act like an equatable thing, like an ordered thing, like an enumerable thing, etc.</p>
<p>Typeclasses are open, which means that we can define our own data type, think about what it can act like and connect it with the typeclasses that define its behaviors. Because of that and because of Haskell's great type system that allows us to know a lot about a function just by knowing its type declaration, we can define typeclasses that define behavior that's very general and abstract. We've met typeclasses that define operations for seeing if two things are equal or comparing two things by some ordering. Those are very abstract and elegant behaviors, but we just don't think of them as anything very special because we've been dealing with them for most of our lives. We recently met functors, which are basically things that can be mapped over. That's an example of a useful and yet still pretty abstract property that typeclasses can describe. In this chapter, we'll take a closer look at functors, along with slightly stronger and more useful versions of functors called applicative functors. We'll also take a look at monoids, which are sort of like socks.</p>
<a name="functors-redux"></a><h2><?=$contents[$_P[0]]['subchapters']['functors-redux']?></h2>
<img src="frogtor.png" alt="frogs dont even need money" class="right" width="369" height="243">
<p>We've already talked about functors in <a href="making-our-own-types-and-typeclasses#the-functor-typeclass">their own little section</a>. If you haven't read it yet, you should probably give it a glance right now, or maybe later when you have more time. Or you can just pretend you read it.</p>
<p>Still, here's a quick refresher: Functors are things that can be mapped over, like lists, <span class="fixed">Maybe</span>s, trees, and such. In Haskell, they're described by the typeclass <span class="fixed">Functor</span>, which has only one typeclass method, namely <span class="fixed">fmap</span>, which has a type of <span class="fixed">fmap :: (a -&gt; b) -&gt; f a -&gt; f b</span>. It says: give me a function that takes an <span class="fixed">a</span> and returns a <span class="fixed">b</span> and a box with an <span class="fixed">a</span> (or several of them) inside it and I'll give you a box with a <span class="fixed">b</span> (or several of them) inside it. It kind of applies the function to the element inside the box.</p>
<div class="hintbox"><em>A word of advice.</em> Many times the box analogy is used to help you get some intuition for how functors work, and later, we'll probably use the same analogy for applicative functors and monads. It's an okay analogy that helps people understand functors at first, just don't take it too literally, because for some functors the box analogy has to be stretched really thin to still hold some truth. A more correct term for what a functor is would be <i>computational context</i>. The context might be that the computation can have a value or it might have failed (<span class="fixed">Maybe</span> and <span class="fixed">Either a</span>) or that there might be more values (lists), stuff like that.</div>
<p>If we want to make a type constructor an instance of <span class="fixed">Functor</span>, it has to have a kind of <span class="fixed">* -&gt; *</span>, which means that it has to take exactly one concrete type as a type parameter. For example, <span class="fixed">Maybe</span> can be made an instance because it takes one type parameter to produce a concrete type, like <span class="fixed">Maybe Int</span> or <span class="fixed">Maybe String</span>. If a type constructor takes two parameters, like <span class="fixed">Either</span>, we have to partially apply the type constructor until it only takes one type parameter. So we can't write <span class="fixed">instance Functor Either where</span>, but we can write <span class="fixed">instance Functor (Either a) where</span> and then if we imagine that <span class="fixed">fmap</span> is only for <span class="fixed">Either a</span>, it would have a type declaration of <span class="fixed">fmap :: (b -&gt; c) -&gt; Either a b -&gt; Either a c</span>. As you can see, the <span class="fixed">Either a</span> part is fixed, because <span class="fixed">Either a</span> takes only one type parameter, whereas just <span class="fixed">Either</span> takes two so <span class="fixed">fmap :: (b -&gt; c) -&gt; Either b -&gt; Either c</span> wouldn't really make sense.</p>
<p>We've learned by now how a lot of types (well, type constructors really) are instances of <span class="fixed">Functor</span>, like <span class="fixed">[]</span>, <span class="fixed">Maybe</span>, <span class="fixed">Either a</span> and a <span class="fixed">Tree</span> type that we made on our own. We saw how we can map functions over them for great good. In this section, we'll take a look at two more instances of functor, namely <span class="fixed">IO</span> and <span class="fixed">(-&gt;) r</span>.</p>
<p>If some value has a type of, say, <span class="fixed">IO String</span>, that means that it's an I/O action that, when performed, will go out into the real world and get some string for us, which it will yield as a result. We can use <span class="fixed">&lt;-</span> in <i>do</i> syntax to bind that result to a name. We mentioned that I/O actions are like boxes with little feet that go out and fetch some value from the outside world for us. We can inspect what they fetched, but after inspecting, we have to wrap the value back in <span class="fixed">IO</span>. By thinking about this box with little feet analogy, we can see how <span class="fixed">IO</span> acts like a functor.</p>
<p>The <span class="fixed">IO a</span> type doesn't expose any value constructors, because I/O is implementation dependent. This forces us to keep our I/O code and our pure code separated. That's why we can't see exactly how <span class="fixed">IO</span> is an instance of <span class="fixed">Functor</span>, but we can play around with it to gain some intuition. It's pretty simple really. Check out this piece of code:</p>
<pre name="code" class="haskell:hs">
main = do line &lt;- getLine 
          let line' = reverse line
          putStrLn "You said " ++ line' ++ " backwards!"
          putStrLn "Yes, you really said" ++ line' ++ " backwards!"
</pre>
<p>The user is prompted for a line and we give it back to the user, only reversed. Here's how to rewrite this by using <span class="fixed">fmap</span>:</p>
<pre name="code" class="haskell:hs">
main = do line &lt;- fmap reverse getLine
          putStrLn "You said " ++ line ++ " backwards!"
          putStrLn "Yes, you really said" ++ line ++ " backwards!"
</pre>
<img src="alien.png" alt="w00ooOoooOO" class="left" width="262" height="212">
<p>Just like when we <span class="fixed">fmap</span> <span class="fixed">reverse</span> over <span class="fixed">Just "blah"</span> to get <span class="fixed">Just "halb"</span>, we can <span class="fixed">fmap</span> <span class="fixed">reverse</span> over <span class="fixed">getLine</span>. <span class="fixed">getLine</span> is an I/O action that has a type of <span class="fixed">IO String</span> and mapping <span class="fixed">reverse</span> over it gives us an I/O action that will go out into the real world and get a line and then apply <span class="fixed">reverse</span> to its result. Like we can apply a function to something that's inside a <span class="fixed">Maybe</span> box, we can apply a function to what's inside an <span class="fixed">IO</span> box, only it has to go out into the real world to get something. Then when we bind it to a name by using <span class="fixed">&lt;-</span>, the name will reflect the result that already has <span class="fixed">reverse</span> applied to it.</p>
<p>If we look at what <span class="fixed">fmap</span>'s type would be if it were limited to <span class="fixed">IO</span>, it would be <span class="fixed">fmap :: (a -&gt; b) -&gt; IO a -&gt; IO b</span>. <span class="fixed">fmap</span> takes a function and an I/O action and returns a new I/O action that's like the old one, except that the function is applied to its contained result.</p>
<p>If you ever find yourself binding the result of an I/O action to a name, only to apply a function to that and call that something else, consider using <span class="fixed">fmap</span>, because it looks prettier. If you want to apply multiple transformations to some data inside a functor, you can declare your own function at the top level, make a lambda function or ideally, use function composition:</p>
<pre name="code" class="haskell:hs">
import Data.Char
import Data.List

main = do line &lt;- fmap (intersperse '-' . reverse . map toUpper) getLine
          putStrLn line
</pre>
<pre name="code" class="plain">
$ runhaskell fmapping_io.hs
hello there
E-R-E-H-T- -O-L-L-E-H
</pre>
<p>As you probably know, <span class="fixed">intersperse '-' . reverse . map toUpper</span> is a function that takes a string, maps <span class="fixed">toUpper</span> over it, the applies <span class="fixed">reverse</span> to that result and then applies <span class="fixed">intersperse '-'</span> to that result. It's like writing <span class="fixed">(\xs -&gt; intersperse '-' (reverse (map toUpper xs)))</span>, only prettier.</p>
<p>Another instance of <span class="fixed">Functor</span> that we've been dealing with all along but didn't know was a <span class="fixed">Functor</span> is <span class="fixed">(-&gt;) r</span>. You're probably slightly confused now, since what the heck does <span class="fixed">(-&gt;) r</span> mean? The function type <span class="fixed">r -&gt; a</span> can be rewritten as <span class="fixed">(-&gt;) r a</span>, much like we can write <span class="fixed">2 + 3</span> as <span class="fixed">(+) 2 3</span>. When we look at it as <span class="fixed">(-&gt;) r a</span>, we can see  <span class="fixed">(-&gt;)</span> in a slighty different light, because we see that it's just a type constructor that takes two type parameters, just like <span class="fixed">Either</span>. But remember, we said that a type constructor has to take exactly one type parameter so that it can be made an instance of <span class="fixed">Functor</span>. That's why we can't make <span class="fixed">(-&gt;)</span> an instance of <span class="fixed">Functor</span>, but if we partially apply it to <span class="fixed">(-&gt;) r</span>, it doesn't pose any problems. If the syntax allowed for type constructors to be partially applied with sections (like we can partially apply <span class="fixed">+</span> by doing <span class="fixed">(2+)</span>, which is the same as <span class="fixed">(+) 2</span>), you could write <span class="fixed">(-&gt;) r</span> as <span class="fixed">(r -&gt;)</span>. How are functions functors? Well, let's take a look at the implementation, which lies in <span class="fixed">Control.Monad.Instances</span></p>
<div class="hintbox">We usually mark functions that take anything and return anything as <span class="fixed">a -&gt; b</span>. <span class="fixed">r -&gt; a</span> is the same thing, we just used different letters for the type variables.</div>
<pre name="code" class="haskell:hs">
instance Functor ((-&gt;) r) where
    fmap f g = (\x -&gt; f (g x))
</pre>
<p>If the syntax allowed for it, it could have been written as</p>
<pre name="code" class="haskell:hs">
instance Functor (r -&gt;) where
    fmap f g = (\x -&gt; f (g x))
</pre>
<p>But it doesn't, so we have to write it in the former fashion.</p>
<p>First of all, let's think about <span class="fixed">fmap</span>'s type. It's <span class="fixed">fmap :: (a -&gt; b) -&gt; f a -&gt; f b</span>. Now what we'll do is mentally replace all the <span class="fixed">f</span>'s, which are the role that our functor instance plays, with <span class="fixed">(-&gt;) r</span>'s. We'll do that to see how <span class="fixed">fmap</span> should behave for this particular instance. We get <span class="fixed">fmap :: (a -&gt; b) -&gt; ((-&gt;) r a) -&gt; ((-&gt;) r b)</span>. Now what we can do is write the <span class="fixed">(-&gt;) r a</span> and <span class="fixed">(-&gt; r b)</span> types as infix <span class="fixed">r -&gt; a</span> and <span class="fixed">r -&gt; b</span>, like we normally do with functions. What we get now is <span class="fixed">fmap :: (a -&gt; b) -&gt; (r -&gt; a) -&gt; (r -&gt; b)</span>.</p>
<p>Hmmm OK. Mapping one function over a function has to produce a function, just like mapping a function over a <span class="fixed">Maybe</span> has to produce a <span class="fixed">Maybe</span> and mapping a function over a list has to produce a list. What does the type <span class="fixed">fmap :: (a -&gt; b) -&gt; (r -&gt; a) -&gt; (r -&gt; b)</span> for this instance tell us? Well, we see that it takes a function from <span class="fixed">a</span> to <span class="fixed">b</span> and a function from <span class="fixed">r</span> to <span class="fixed">a</span> and returns a function from <span class="fixed">r</span> to <span class="fixed">b</span>. Does this remind you of anything? Yes! Function composition! We pipe the output of <span class="fixed">r -&gt; a</span> into the input of <span class="fixed">a -&gt; b</span> to get a function <span class="fixed">r -&gt; b</span>, which is exactly what function composition is about. If you look at how the instance is defined above, you'll see that it's just function composition. Another way to write this instance would be:</p>
<pre name="code" class="haskell:hs">
instance Functor ((-&gt;) r) where
    fmap = (.)
</pre>
<p>This makes the revelation that using <span class="fixed">fmap</span> over functions is just composition sort of obvious. Do <span class="fixed">:m + Control.Monad.Instances</span>, since that's where the instance is defined and then try playing with mapping over functions.</p>
<pre name="code" class="haskell:hs">
ghci&gt; :t fmap (*3) (+100)
fmap (*3) (+100) :: (Num a) =&gt; a -&gt; a
ghci&gt; fmap (*3) (+100) 1
303
ghci&gt; (*3) `fmap` (+100) $ 1
303
ghci&gt; (*3) . (+100) $ 1
303
ghci&gt; fmap (show . (*3)) (*100) 1
"300"
</pre>
<p>We can call <span class="fixed">fmap</span> as an infix function so that the resemblance to <span class="fixed">.</span> is clear. In the second input line, we're mapping <span class="fixed">(*3)</span> over <span class="fixed">(+100)</span>, which results in a function that will take an input, call <span class="fixed">(+100)</span> on that and then call <span class="fixed">(*3)</span> on that result. We call that function with <span class="fixed">1</span>.</p>
<p>How does the box analogy hold here? Well, if you stretch it, it holds. When we use <span class="fixed">fmap (+3)</span> over <span class="fixed">Just 3</span>, it's easy to imagine the <span class="fixed">Maybe</span> as a box that has some contents on which we apply the function <span class="fixed">(*3)</span>. But what about when we're doing <span class="fixed">fmap (*3) (+100)</span>? Well, you can think of the function <span class="fixed">(+100)</span> as a box that contains its eventual result. Sort of like how an I/O action can be thought of as a box that will go out into the real world and fetch some result. Using <span class="fixed">fmap (*3)</span> on <span class="fixed">(+100)</span> will create another function that acts like <span class="fixed">(+100)</span>, only before producing a result, <span class="fixed">(*3)</span> will be applied to that result. Now we can see how <span class="fixed">fmap</span> acts just like <span class="fixed">.</span> for functions.</p>
<p>The fact that <span class="fixed">fmap</span> is function composition when used on functions isn't so terribly useful right now, but at least it's very interesting. It also bends our minds a bit and let us see how things that act more like computations than boxes (<span class="fixed">IO</span> and <span class="fixed">(-&gt;) r</span>) can be functors. The function being mapped over a computation results in the same computation but the result of that computation is modified with the function.</p>
<img src="lifter.png" alt="lifting a function is easier than lifting a million pounds" class="right" width="443" height="450">
<p>Before we go on to the rules that <span class="fixed">fmap</span> should follow, let's think about the type of <span class="fixed">fmap</span> once more. Its type is <span class="fixed">fmap :: (a -&gt; b) -&gt; f a -&gt; f b</span>. We're missing the class constraint <span class="fixed">(Functor f) =&gt;</span>, but we left it out here for brevity, because we're talking about functors anyway so we know what the <span class="fixed">f</span> stands for. When we first learned about <a href="higher-order-functions#curried-functions">curried functions</a>, we said that all Haskell functions actually take one parameter. A function <span class="fixed">a -&gt; b -&gt; c</span> actually takes just one parameter of type <span class="fixed">a</span> and then returns a function <span class="fixed">b -&gt; c</span>, which takes one parameter and returns a <span class="fixed">c</span>. That's how if we call a function with too few parameters (i.e. partially apply it), we get back a function that takes the number of parameters that we left out (if we're thinking about functions as taking several parameters again). So <span class="fixed">a -&gt; b -&gt; c</span> can be written as <span class="fixed">a -&gt; (b -&gt; c)</span>, to make the currying more apparent.</p>
<p>In the same vein, if we write <span class="fixed">fmap :: (a -&gt; b) -&gt; (f a -&gt; f b)</span>, we can think of <span class="fixed">fmap</span> not as a function that takes one function and a functor and returns a functor, but as a function that takes a function and returns a new function that's just like the old one, only it takes a functor as a parameter and returns a functor as the result. It takes an <span class="fixed">a -&gt; b</span> function and returns a function <span class="fixed">f a -&gt; f b</span>. This is called <i>lifting</i> a function. Let's play around with that idea by using GHCI's <span class="fixed">:t</span> command:</p>
<pre name="code" class="haskell:hs">
ghci&gt; :t fmap (*2)
fmap (*2) :: (Num a, Functor f) =&gt; f a -&gt; f a
ghci&gt; :t fmap (replicate 3)
fmap (replicate 3) :: (Functor f) =&gt; f a -&gt; f [a]
</pre>
<p>The expression <span class="fixed">fmap (*2)</span> is a function that takes a functor <span class="fixed">f</span> over numbers and returns a functor over numbers. That functor can be a list, a <span class="fixed">Maybe </span>, an <span class="fixed">Either String</span>, whatever. The expression <span class="fixed">fmap (replicate 3)</span> will take a functor over any type and return a functor over a list of elements of that type.</p>
<div class="hintbox">When we say <i>a functor over numbers</i>, you can think of that as <i>a functor that has numbers in it</i>. The former is a bit fancier and more technically correct, but the latter is usually easier to get.</div
<p>This is even more apparent if we partially apply, say, <span class="fixed">fmap (++"!")</span> and then bind it to a name in GHCI.</p>
<p>You can think of <span class="fixed">fmap</span> as either a function that takes a function and a functor and then maps that function over the functor, or you can think of it as a function that takes a function and lifts that function so that it operates on functors. Both views are correct and in Haskell, equivalent.</p>
<p>The type <span class="fixed">fmap (replicate 3) :: (Functor f) =&gt; f a -&gt; f [a]</span> means that the function will work on any functor. What exactly it will do depends on which functor we use it on. If we use <span class="fixed">fmap (replicate 3)</span> on a list, the list's implementation for <span class="fixed">fmap</span> will be chosen, which is just <span class="fixed">map</span>. If we use it on a <span class="fixed">Maybe a</span>, it'll apply <span class="fixed">replicate 3</span> to the value inside the <span class="fixed">Just</span>, or if it's <span class="fixed">Nothing</span>, then it stays <span class="fixed">Nothing</span>.</p>
<pre name="code" class="haskell:hs">
ghci&gt; fmap (replicate 3) [1,2,3,4]
[[1,1,1],[2,2,2],[3,3,3],[4,4,4]]
ghci&gt; fmap (replicate 3) (Just 4)
Just [4,4,4]
ghci&gt; fmap (replicate 3) (Right "blah")
Right ["blah","blah","blah"]
ghci&gt; fmap (replicate 3) Nothing
Nothing
ghci&gt; fmap (replicate 3) (Left "foo")
Left "foo"
</pre>
<p>Next up, we're going to look at the <em>functor laws</em>. In order for something to be a functor, it should satisfy some laws. All functors are expected to exhibit certain kinds of functor-like properties and behaviors. They should reliably behave as things that can be mapped over. Calling <span class="fixed">fmap</span> on a functor should just map a function over the functor, nothing more. This behavior is described in the functor laws. There are two of them that all instances of <span class="fixed">Functor</span> should abide by. They aren't enforced by Haskell automatically, so you have to test them out yourself.</p>
<p><em>The first functor law states that if we map the <span class="fixed">id</span> function over a functor, the functor that we get back should be the same as the original functor.</em> If we write that a bit more formally, it means that <span class="label law">fmap id = id</span>. So essentially, this says that if we do <span class="fixed">fmap id</span> over a functor, it should be the same as just calling <span class="fixed">id</span> on the functor. Remember, <span class="fixed">id</span> is the identity function, which just returns its parameter unmodified. It can also be written as <span class="fixed">\x -&gt; x</span>. If we view the functor as something that can be mapped over, the <span class="label law">fmap id = id</span> law seems kind of trivial or obvious.</p>
<p>Let's see if this law holds for a few values of functors.</p>
<pre name="code" class="haskell:hs">
ghci&gt; fmap id (Just 3)
Just 3
ghci&gt; id (Just 3)
Just 3
ghci&gt; fmap id [1..5]
[1,2,3,4,5]
ghci&gt; id [1..5]
[1,2,3,4,5]
ghci&gt; fmap id []
[]
ghci&gt; fmap id Nothing
Nothing
</pre>
<p>If we look at the implementation of <span class="fixed">fmap</span> for, say, <span class="fixed">Maybe</span>, we can figure out why the first functor law holds.</p>
<pre name="code" class="haskell:hs">
instance Functor Maybe where
    fmap f (Just x) = Just (f x)
    fmap f Nothing = Nothing
</pre>
<p>We imagine that <span class="fixed">id</span> plays the role of the <span class="fixed">f</span> parameter in the implementation. We see that if wee <span class="fixed">fmap id</span> over <span class="fixed">Just x</span>, the result will be <span class="fixed">Just (id x)</span>, and because <span class="fixed">id</span> just returns its parameter, we can deduce that <span class="fixed">Just (id x)</span> equals <span class="fixed">Just x</span>. So now we know that if we map <span class="fixed">id</span> over a <span class="fixed">Maybe</span> value with a <span class="fixed">Just</span> value constructor, we get that same value back.</p>
<p>Seeing that mapping <span class="fixed">id</span> over a <span class="fixed">Nothing</span> value returns the same value is trivial. So from these two equations in the implementation for <span class="fixed">fmap</span>, we see that the law <span class="fixed">fmap id = id</span> holds.</p>
<img src="justice.png" alt="justice is blind, but so is my dog" class="left" width="345" height="428">
<p><em>The second law says that composing two functions and then mapping the resulting function over a functor should be the same as first mapping one function over the functor and then mapping the other one.</em> Formally written, that means that <span class="label law">fmap (f . g) = fmap f . fmap g</span>. Or to write it in another way, for any functor <i>F</i>, the following should hold: <span class="label law">fmap (f . g) F = fmap f (fmap g F)</span>.</p>
<p>If we can show that some type obeys both functor laws, we can rely on it having the same fundamental behaviors as other functors when it comes to mapping. We can know that when we use <span class="fixed">fmap</span> on it, there won't be anything other than mapping going on behind the scenes and that it will act like a thing that can be mapped over, i.e. a functor. You figure out how the second law holds for some type by looking at the implementation of <span class="fixed">fmap</span> for that type and then using the method that we used to check if <span class="fixed">Maybe</span> obeys the first law.</p>
<p>If you want, we can check out how the second functor law holds for <span class="fixed">Maybe</span>. If we do <span class="fixed">fmap (f . g)</span> over <span class="fixed">Nothing</span>, we get <span class="fixed">Nothing</span>, because doing a <span class="fixed">fmap</span> with any function over <span class="fixed">Nothing</span> returns <span class="fixed">Nothing</span>. If we do <span class="fixed">fmap f (fmap g Nothing)</span>, we get <span class="fixed">Nothing</span>, for the same reason. OK, seeing how the second law holds for <span class="fixed">Maybe</span> if it's a <span class="fixed">Nothing</span> value is pretty easy, almost trivial. </p><p>How about if it's a <span class="fixed">Just <i>something</i></span> value? Well, if we do <span class="fixed">fmap (f . g) (Just x)</span>, we see from the implementation that it's implemented as <span class="fixed">Just ((f . g) x)</span>, which is, of course, <span class="fixed">Just (f (g x))</span>. If we do <span class="fixed">fmap f (fmap g (Just x))</span>, we see from the implementation that <span class="fixed">fmap g (Just x)</span> is <span class="fixed">Just (g x)</span>. Ergo, <span class="fixed">fmap f (fmap g (Just x))</span> equals <span class="fixed">fmap f (Just (g x))</span> and from the implementation we see that this equals <span class="fixed">Just (f (g x))</span>.</p>
<p>If you're a bit confused by this proof, don't worry. Be sure that you understand how <a href="higher-order-functions#composition">function composition</a> works. Many times, you can intuitively see how these laws hold because the types act like containers or functions. You can also just try them on a bunch of different values of a type and be able to say with some certainty that a type does indeed obey the laws.</p>
<p>Let's take a look at a pathological example of a type constructor being an instance of the <span class="fixed">Functor</span> typeclass but not really being a functor, because it doesn't satisfy the laws. Let's say that we have a type:</p>
<pre name="code" class="haskell:hs">
data CMaybe a = CNothing | CJust Int a deriving (Show)
</pre>
<p>The C here stands for <i>counter</i>. It's a data type that looks much like <span class="fixed">Maybe a</span>, only the <span class="fixed">Just</span> part holds two fields instead of one. The first field in the <span class="fixed">CJust</span> value constructor will always have a type of <span class="fixed">Int</span>, and it will be some sort of counter and the second field is of type <span class="fixed">a</span>, which comes from the type parameter and its type will, of course, depend on the concrete type that we choose for <span class="fixed">CMaybe a</span>. Let's play with our new type to get some intuition for it.</p>
<pre name="code" class="haskell:hs">
ghci&gt; CNothing
CNothing
ghci&gt; CJust 0 "haha"
CJust 0 "haha"
ghci&gt; :t CNothing
CNothing :: CMaybe a
ghci&gt; :t CJust 0 "haha"
CJust 0 "haha" :: CMaybe [Char]
ghci&gt; CJust 100 [1,2,3]
CJust 100 [1,2,3]
</pre>
<p>If we use the <span class="fixed">CNothing</span> constructor, there are no fields, and if we use the <span class="fixed">CJust</span> constructor, the first field is an integer and the second field can be any type. Let's make this an instance of <span class="fixed">Functor</span> so that everytime we use <span class="fixed">fmap</span>, the function gets applied to the second field, whereas the first field gets increased by 1.</p>
<pre name="code" class="haskell:hs">
instance Functor CMaybe where
    fmap f CNothing = CNothing
    fmap f (CJust counter x) = CJust (counter+1) (f x)
</pre>
<p>This is kind of like the instance implementation for <span class="fixed">Maybe</span>, except that when we do <span class="fixed">fmap</span> over a value that doesn't represent an empty box (a <span class="fixed">CJust</span> value), we don't just apply the function to the contents, we also increase the counter by 1. Everything seems cool so far, we can even play with this a bit:</p>
<pre name="code" class="haskell:hs">
ghci&gt; fmap (++"ha") (CJust 0 "ho")
CJust 1 "hoha"
ghci&gt; fmap (++"he") (fmap (++"ha") (CJust 0 "ho"))
CJust 2 "hohahe"
ghci&gt; fmap (++"blah") CNothing
CNothing
</pre>
<p>Does this obey the functor laws? In order to see that something doesn't obey a law, it's enough to find just one counter-example.</p>
<pre name="code" class="haskell:hs">
ghci&gt; fmap id (CJust 0 "haha")
CJust 1 "haha"
ghci&gt; id (CJust 0 "haha")
CJust 0 "haha"
</pre>
<p>Ah! We know that the first functor law states that if we map <span class="fixed">id</span> over a functor, it should be the same as just calling <span class="fixed">id</span> with the same functor, but as we've seen from this example, this is not true for our <span class="fixed">CMaybe</span> functor. Even though it's part of the <span class="fixed">Functor</span> typeclass, it doesn't obey the functor laws and is therefore not a functor. If someone used our <span class="fixed">CMaybe</span> type as a functor, they would expect it to obey the functor laws like a good functor. But <span class="fixed">CMaybe</span> fails at being a functor even though it pretends to be one, so using it as a functor might lead to some faulty code. When we use a functor, it shouldn't matter if we first compose a few functions and then map them over the functor or if we just map each function over a functor in succession. But with <span class="fixed">CMaybe</span>, it matters, because it keeps track of how many times it's been mapped over. Not cool! If we wanted <span class="fixed">CMaybe</span> to obey the functor laws, we'd have to make it so that the <span class="fixed">Int</span> field stays the same when we use <span class="fixed">fmap</span>.</p>
<p>At first, the functor laws might seem a bit confusing and unnecessary, but then we see that if we know that a type obeys both laws, we can make certain assumptions about how it will act. If a type obeys the functor laws, we know that calling <span class="fixed">fmap</span> on a value of that type will only map the function over it, nothing more. This leads to code that is more abstract and extensible, because we can use laws to reason about behaviors that any functor should have and make functions that operate reliably on any functor.</p>
<p>All the <span class="fixed">Functor</span> instances in the standard library obey these laws, but you can check for yourself if you don't believe me. And the next time you make a type an instance of <span class="fixed">Functor</span>, take a minute to make sure that it obeys the functor laws. Once you've dealt with enough functors, you kind of intuitively see the properties and behaviors that they have in common and it's not hard to intuitively see if a type obeys the functor laws. But even without the intuition, you can always just go over the implementation line by line and see if the laws hold or try to find a counter-example.</p>
<p>We can also look at functors as things that output values in a context. For instance, <span class="fixed">Just 3</span> outputs the value <span class="fixed">3</span> in the context that it might or not output any values at all. <span class="fixed">[1,2,3]</span> outputs three values&mdash;<span class="fixed">1</span>, <span class="fixed">2</span>, and <span class="fixed">3</span>, the context is that there may be multiple values or no values. The function <span class="fixed">(+3)</span> will output a value, depending on which parameter it is given.</p>
<p>If you think of functors as things that output values, you can think of mapping over functors as attaching a transformation to the output of the functor that changes the value. When we do <span class="fixed">fmap (+3) [1,2,3]</span>, we attach the transformation <span class="fixed">(+3)</span> to the output of <span class="fixed">[1,2,3]</span>, so whenever we look at a number that the list outputs, <span class="fixed">(+3)</span> will be applied to it. Another example is mapping over functions. When we do <span class="fixed">fmap (+3) (*3)</span>, we attach the transformation <span class="fixed">(+3)</span> to the eventual output of <span class="fixed">(*3)</span>. Looking at it this way gives us some intuition as to why using <span class="fixed">fmap</span> on functions is just composition (<span class="fixed">fmap (+3) (*3)</span> equals <span class="fixed">(+3) . (*3)</span>, which equals <span class="fixed">\x -&gt; ((x*3)+3)</span>), because we take a function like <span class="fixed">(*3)</span> then we attach the transformation <span class="fixed">(+3)</span> to its output. The result is still a function, only when we give it a number, it will be multiplied by three and then it will go through the attached transformation where it will be added to three. This is what happens with composition.</p>
<a name="applicative-functors"></a><h2><?=$contents[$_P[0]]['subchapters']['applicative-functors']?></h2>
<img src="present.png" class="right" width="302" height="284" alt="disregard this analogy">
<p>In this section, we'll take a look at applicative functors, which are beefed up functors, represented in Haskell by the <span class="fixed">Applicative</span> typeclass, found in the <span class="fixed">Control.Applicative</span> module.</p>
<p>As you know, functions in Haskell are curried by default, which means that a function that seems to take several parameters actually takes just one parameter and returns a function that takes the next parameter and so on. If a function is of type <span class="fixed">a -&gt; b -&gt; c</span>, we usually say that it takes two parameters and returns a <span class="fixed">c</span>, but actually it takes an <span class="fixed">a</span> and returns a function <span class="fixed">b -&gt; c</span>. That's why we can call a function as <span class="fixed">f x y</span> or as <span class="fixed">(f x) y</span>. This mechanism is what enables us to partially apply functions by just calling them with too few parameters, which results in functions that we can then pass on to other functions.</p>
<p>So far, when we were mapping functions over functors, we usually mapped functions that take only one parameter. But what happens when we map a function like <span class="fixed">*</span>, which takes two parameters, over a functor? Let's take a look at a couple of concrete examples of this. If we have <span class="fixed">Just 3</span> and we do <span class="fixed">fmap (*) (Just 3)</span>, what do we get? From the instance implementation of <span class="fixed">Maybe</span> for <span class="fixed">Functor</span>, we know that if it's a <span class="fixed">Just <i>something</i></span> value, it will apply the function to the <span class="fixed"><i>something</i></span> inside the <span class="fixed">Just</span>. Therefore, doing <span class="fixed">fmap (*) (Just 3)</span> results in <span class="fixed">Just ((*) 3)</span>, which can also be written as <span class="fixed">Just (3 *)</span> if we use sections. Interesting! We get a function wrapped in a <span class="fixed">Just</span>!</p> 
<pre name="code" class="haskell:hs">
ghci&gt; :t fmap (++) (Just "hey")
fmap (++) (Just "hey") :: Maybe ([Char] -&gt; [Char])
ghci&gt; :t fmap compare (Just 'a')
fmap compare (Just 'a') :: Maybe (Char -&gt; Ordering)
ghci&gt; :t fmap compare "A LIST OF CHARS"
fmap compare "A LIST OF CHARS" :: [Char -&gt; Ordering]
ghci&gt; :t fmap (\x y z -&gt; x + y / z) [3,4,5,6]
fmap (\x y z -&gt; x + y / z) [3,4,5,6] :: (Fractional a) =&gt; [a -&gt; a -&gt; a]
</pre>
<p>If we map <span class="fixed">compare</span>, which has a type of <span class="fixed">(Ord a) =&gt; a -&gt; a -&gt; Ordering</span> over a list of characters, we get a list of functions of type <span class="fixed">Char -&gt; Ordering</span>, because the function <span class="fixed">compare</span> gets partially applied with the characters in the list. It's not a list of <span class="fixed">(Ord a) =&gt; a -&gt; Ordering</span> function, because the first <span class="fixed">a</span> that got applied was a <span class="fixed">Char</span> and so the second <span class="fixed">a</span> has to decide to be of type <span class="fixed">Char</span>.</p>
<p>We see how by mapping "multi-parameter" functions over functors, we get functors that contain functions inside them. So now what can we do with them? Well for one, we can map functions that take these functions as parameters over them, because whatever is inside a functor will be given to the function that we're mapping over it as a parameter.</p>
<pre name="code" class="haskell:hs">
ghci&gt; let a = fmap (*) [1,2,3,4]
ghci&gt; :t a
a :: [Integer -&gt; Integer]
ghci&gt; fmap (\f -&gt; f 9) a
[9,18,27,36]
</pre>
<p>But what if we have a functor value of <span class="fixed">Just (3 *)</span> and a functor value of <span class="fixed">Just 5</span> and we want to take out the function from <span class="fixed">Just (3 *)</span> and map it over <span class="fixed">Just 5</span>? With normal functors, we're out of luck, because all they support is just mapping normal functions over existing functors. Even when we mapped <span class="fixed">\f -&gt; f 9</span> over a functor that contained functions inside it, we were just mapping a normal function over it. But we can't map a function that's inside a functor over another functor with what <span class="fixed">fmap</span> offers us. We could pattern-match against the <span class="fixed">Just</span> constructor to get the function out of it and then map it over <span class="fixed">Just 5</span>, but we're looking for a more general and abstract way of doing that, which works across functors.</p>
<p>Meet the <span class="fixed">Applicative</span> typeclass. It lies in the <span class="fixed">Control.Applicative</span> module and it defines two methods, <span class="fixed">pure</span> and <span class="fixed">&lt;*&gt;</span>. It doesn't provide a default implementation for any of them, so we have to define them both if we want something to be an applicative functor. The class is defined like so:</p>
<pre name="code" class="haskell:hs">
class (Functor f) =&gt; Applicative f where
    pure :: a -&gt; f a
    (&lt;*&gt;) :: f (a -&gt; b) -&gt; f a -&gt; f b
</pre>
<p>This simple three line class definition tells us a lot! Let's start at the first line. It starts the definition of the <span class="fixed">Applicative</span> class and it also introduces a class constraint. It says that if we want to make a type constructor part of the <span class="fixed">Applicative</span> typeclass, it has to be in <span class="fixed">Functor</span> first. That's why if we know that if a type constructor is part of the <span class="fixed">Applicative</span> typeclass, it's also in <span class="fixed">Functor</span>, so we can use <span class="fixed">fmap</span> on it.</p>
<p>The first method it defines is called <span class="fixed">pure</span>. Its type declaration is <span class="fixed">pure :: a -&gt; f a</span>. <span class="fixed">f</span> plays the role of our applicative functor instance here. Because Haskell has a very good type system and because everything a function can do is take some parameters and return some value, we can tell a lot from a type declaration and this is no exception. <span class="fixed">pure</span> should take a value of any type and return an applicative functor with that value inside it. When we say <i>inside it</i>, we're using the box analogy again, even though we've seen that it doesn't always stand up to scrutiny. But the <span class="fixed">a -&gt; f a</span> type declaration is still pretty descriptive. We take a value and we wrap it in an applicative functor that has that value as the result inside it.</p>
<p>A better way of thinking about <span class="fixed">pure</span> would be to say that it takes a value and puts it in some sort of default (or pure) context&mdash;a minimal context that still yields that value.</p>
<p>The <span class="fixed">&lt;*&gt;</span> function is really interesting. It has a type declaration of <span class="fixed">f (a -&gt; b) -&gt; f a -&gt; f b</span>. Does this remind you of anything? Of course, <span class="fixed">fmap :: (a -&gt; b) -&gt; f a -&gt; f b</span>. It's a sort of a beefed up <span class="fixed">fmap</span>. Whereas <span class="fixed">fmap</span> takes a function and a functor and applies the function inside the functor, <span class="fixed">&lt;*&gt;</span> takes a functor that has a function in it and another functor and sort of extracts that function from the first functor and then maps it over the second one. When I say <i>extract</i>, I actually sort of mean <i>run</i> and then extract, maybe even <i>sequence</i>. We'll see why soon. 
<p>Let's take a look at the <span class="fixed">Applicative</span> instance implementation for <span class="fixed">Maybe</span>.</p>
<pre name="code" class="haskell:hs">
instance Applicative Maybe where
    pure = Just
    Nothing &lt;*&gt; _ = Nothing
    (Just f) &lt;*&gt; something = fmap f something
</pre>
<p>Again, from the class definition we see that the <span class="fixed">f</span> that plays the role of the applicative functor should take one concrete type as a parameter, so we write <span class="fixed">instance Applicative Maybe where</span> instead of writing <span class="fixed">instance Applicative (Maybe a) where</span>.</p>
<p>First off, <span class="fixed">pure</span>. We said earlier that it's supposed to take something and wrap it in an applicative functor. We wrote <span class="fixed">pure = Just</span>, because value constructors like <span class="fixed">Just</span> are normal functions. We could have also written <span class="fixed">pure x = Just x</span>.</p>
<p>Next up, we have the definition for <span class="fixed">&lt;*&gt;</span>. We can't extract a function out of a <span class="fixed">Nothing</span>, because it has no function inside it. So we say that if we try to extract a function from a <span class="fixed">Nothing</span>, the result is a <span class="fixed">Nothing</span>. If you look at the class definition for <span class="fixed">Applicative</span>, you'll see that there's a <span class="fixed">Functor</span> class constraint, which means that we can assume that both of <span class="fixed">&lt;*&gt;</span>'s parameters are functors. If the first parameter is not a <span class="fixed">Nothing</span>, but a <span class="fixed">Just</span> with some function inside it, we say that we then want to map that function over the second parameter. This also takes care of the case where the second parameter is <span class="fixed">Nothing</span>, because doing <span class="fixed">fmap</span> with any function over a <span class="fixed">Nothing</span> will return a <span class="fixed">Nothing</span>.</p>
<p>So for <span class="fixed">Maybe</span>, <span class="fixed">&lt;*&gt;</span> extracts the function from the left value if it's a <span class="fixed">Just</span> and maps it over the right value. If any of the parameters is <span class="fixed">Nothing</span>, <span class="fixed">Nothing</span> is the result.</p>
<p>OK cool great. Let's give this a whirl.</p>
<pre name="code" class="haskell:hs">
ghci&gt; Just (+3) &lt;*&gt; Just 9
Just 12
ghci&gt; pure (+3) &lt;*&gt; Just 10
Just 13
ghci&gt; pure (+3) &lt;*&gt; Just 9
Just 12
ghci&gt; Just (++"hahah") &lt;*&gt; Nothing
Nothing
ghci&gt; Nothing &lt;*&gt; Just "woot"
Nothing
</pre>
<p>We see how doing <span class="fixed">pure (+3)</span> and <span class="fixed">Just (+3)</span> is the same in this case. Use <span class="fixed">pure</span> if you're dealing with <span class="fixed">Maybe</span> values in an applicative context (i.e. using them with <span class="fixed">&lt;*&gt;</span>), otherwise stick to <span class="fixed">Just</span>. The first four input lines demonstrate how the function is extracted and then mapped, but in this case, they could have been achieved by just mapping unwrapped functions over functors. The last line is interesting, because we try to extract a function from a <span class="fixed">Nothing</span> and then map it over something, which of course results in a <span class="fixed">Nothing</span>.</p>
<p>With normal functors, you can just map a function over a functor and then you can't get the result out in any general way, even if the result is a partially applied function. Applicative functors, on the other hand, allow you to operate on several functors with a single function. Check out this piece of code:</p>
<pre name="code" class="haskell:hs">
ghci&gt; pure (+) &lt;*&gt; Just 3 &lt;*&gt; Just 5
Just 8
ghci&gt; pure (+) &lt;*&gt; Just 3 &lt;*&gt; Nothing
Nothing
ghci&gt; pure (+) &lt;*&gt; Nothing &lt;*&gt; Just 5
Nothing
</pre>
<img src="whale.png" alt="whaale" class="right" width="214" height="177">
<p>What's going on here? Let's take a look, step by step. <span class="fixed">&lt;*&gt;</span> is left-associative, which means that <span class="fixed">pure (+) &lt;*&gt; Just 3 &lt;*&gt; Just 5</span> is the same as <span class="fixed">(pure (+) &lt;*&gt; Just 3) &lt;*&gt; Just 5</span>. First, the <span class="fixed">+</span> function is put in a functor, which is in this case a <span class="fixed">Maybe</span> value that contains the function. So at first, we have <span class="fixed">pure (+)</span>, which is <span class="fixed">Just (+)</span>. Next, <span class="fixed">Just (+) &lt;*&gt; Just 3</span> happens. The result of this is <span class="fixed">Just (3+)</span>. This is because of partial application. Only applying <span class="fixed">3</span> to the <span class="fixed">+</span> function results in a function that takes one parameter and adds 3 to it. Finally, <span class="fixed">Just (3+) &lt;*&gt; Just 5</span> is carried out, which results in a <span class="fixed">Just 8</span>. </p>
<p>Isn't this awesome?! Applicative functors and the applicative style of doing <span class="fixed">pure f &lt;*&gt; x &lt;*&gt; y &lt;*&gt; ...</span> allow us to take a function that expects parameters that aren't necessarily wrapped in functors and use that function to operate on several values that are in functor contexts. The function can take as many parameters as we want, because it's always partially applied step by step between occurences of <span class="fixed">&lt;*&gt;</span>.</p>
<p>This becomes even more handy and apparent if we consider the fact that <span class="fixed">pure f &lt;*&gt; x</span> equals <span class="fixed">fmap f x</span>. This is one of the applicative laws. We'll take a closer look at them later, but for now, we can sort of intuitively see that this is so. Think about it, it makes sense. Like we said before, <span class="fixed">pure</span> puts a value in a default context. If we just put a function in a default context and then extract and apply it to a value inside another applicative functor, we did the same as just mapping that function over that applicative functor. Instead of writing <span class="fixed">pure f &lt;*&gt; x &lt;*&gt; y &lt;*&gt; ...</span>, we can write <span class="fixed">fmap f x &lt;*&gt; y &lt;*&gt; ...</span>. This is why <span class="fixed">Control.Applicative</span> exports a function called <span class="fixed">&lt;$&gt;</span>, which is just <span class="fixed">fmap</span> as an infix operator. Here's how it's defined:</p>
<pre name="code" class="haskell:hs">
(&lt;$&gt;) :: (Functor f) =&gt; (a -&gt; b) -&gt; f a -&gt; f b
f &lt;$&gt; x = fmap f x
</pre>
<div class="hintbox"><em>Yo!</em> Quick reminder: type variables are independent of parameter names or other value names. The <span class="fixed">f</span> in the function declaration here is a type variable with a class constraint saying that any type constructor that replaces <span class="fixed">f</span> should be in the <span class="fixed">Functor</span> typeclass. The <span class="fixed">f</span> in the function body denotes a function that we map over <span class="fixed">x</span>. The fact that we used <span class="fixed">f</span> to represent both of those doesn't mean that they somehow represent the same thing.</div>
<p>By using <span class="fixed">&lt;$&gt;</span>, the applicative style really shines, because now if we want to apply a function <span class="fixed">f</span> between three applicative functors, we can write <span class="fixed">f &lt;$&gt; x &lt;*&gt; y &lt;*&gt; z</span>. If the parameters weren't applicative functors but normal values, we'd write <span class="fixed">f x y z</span>.</p>
<p>Let's take a closer look at how this works. We have a value of <span class="fixed">Just "johntra"</span> and a value of <span class="fixed">Just "volta"</span> and we want to join them into one <span class="fixed">String</span> inside a <span class="fixed">Maybe</span> functor. We do this:</p>
<pre name="code" class="haskell:hs">
ghci&gt; (++) &lt;$&gt; Just "johntra" &lt;*&gt; Just "volta"
Just "johntravolta"
</pre>
<p>Before we see how this happens, compare the above line with this:</p>
<pre name="code" class="haskell:hs">
ghci&gt; (++) "johntra" "volta"
"johntravolta"
</pre>
<p>Awesome! To use a normal function on applicative functors, just sprinkle some <span class="fixed">&lt;$&gt;</span> and <span class="fixed">&lt;*&gt;</span> about and the function will operate on applicatives and return an applicative. How cool is that?</p>
<p>Anyway, when we do <span class="fixed">(++) &lt;$&gt; Just "johntra" &lt;*&gt; Just "volta"</span>, first <span class="fixed">(++)</span>, which has a type of <span class="fixed">(++) :: [a] -&gt; [a] -&gt; [a]</span> gets mapped over <span class="fixed">Just "johntra"</span>, resulting in a value that's the same as <span class="fixed">Just ("johntra"++)</span> and has a type of <span class="fixed">Maybe ([Char] -&gt; [Char])</span>. Notice how the first parameter of <span class="fixed">(++)</span> got eaten up and how the <span class="fixed">a</span>s turned into <span class="fixed">Char</span>s. And now <span class="fixed">Just ("johntra"++) &lt;*&gt; Just "volta"</span> happens, which takes the function out of the <span class="fixed">Just</span> and maps it over <span class="fixed">Just "volta"</span>, resulting in <span class="fixed">Just "johntravolta"</span>. Had any of the two values been <span class="fixed">Nothing</span>, the result would have also been <span class="fixed">Nothing</span>.</p>
<p>So far, we've only used <span class="fixed">Maybe</span> in our examples and you might be thinking that applicative functors are all about <span class="fixed">Maybe</span>. There are loads of other instances of <span class="fixed">Applicative</span>, so let's go and meet them!</p>
<p>Lists (actually the list type constructor, <span class="fixed">[]</span>) are applicative functors. What a suprise! Here's how <span class="fixed">[]</span> is an instance of <span class="fixed">Applicative</span>:</p>
<pre name="code" class="haskell:hs">
instance Applicative [] where
    pure x = [x]
    fs &lt;*&gt; xs = [f x | f &lt;- fs, x &lt;- xs]
</pre>
<p>Earlier, we said that <span class="fixed">pure</span> takes a value and puts it in a default context. Or in other words, a minimal context that still yields that value. The minimal context for lists would be the empty list, <span class="fixed">[]</span>, but the empty list represents the lack of a value, so it can't hold in itself the value that we used <span class="fixed">pure</span> on. That's why <span class="fixed">pure</span> takes a value and puts it in a singleton list. Similarly, the minimal context for the <span class="fixed">Maybe</span> applicative functor would be a <span class="fixed">Nothing</span>, but it represents the lack of a value instead of a value, so <span class="fixed">pure</span> is implemented as <span class="fixed">Just</span> in the instance implementation for <span class="fixed">Maybe</span>.</p>
<pre name="code" class="haskell:hs">
ghci&gt; pure "Hey" :: [String]
["Hey"]
ghci&gt; pure "Hey" :: Maybe String
Just "Hey"
</pre>
<p>What about <span class="fixed">&lt;*&gt;</span>? If we look at what <span class="fixed">&lt;*&gt;</span>'s type would be if it were limited only to lists, we get <span class="fixed">(&lt;*&gt;) :: [a -&gt; b] -&gt; [a] -&gt; [b]</span>. It's implemented with a <a href="starting-out#im-a-list-comprehension">list comprehension</a>. <span class="fixed">&lt;*&gt;</span> has to somehow extract the function out of its left parameter and then map it over the right parameter. But the thing here is that the left list can have zero functions, one function, or several functions inside it. The right list can also hold several values. That's why we use a list comprehension to draw from both lists. We apply every possible function from the left list to every possible value from the right list. The resulting list has every possible combination of applying a function from the left list to a value in the right one.</p>
<pre name="code" class="haskell:hs">
ghci&gt; [(*0),(+100),(^2)] &lt;*&gt; [1,2,3]
[0,0,0,101,102,103,1,4,9]
</pre>
<p>The left list has three functions and the right list has three values, so the resulting list will have nine elements. Every function in the left list is applied to every function in the right one. If we have a list of functions that take two parameters, we can apply those functions between two lists.</p>
<pre name="code" class="haskell:hs">
ghci&gt; [(+),(*)] &lt;*&gt; [1,2] &lt;*&gt; [3,4]
[4,5,5,6,3,4,6,8]
</pre>
<p>Because <span class="fixed">&lt;*&gt;</span> is left-associative, <span class="fixed">[(+),(*)] &lt;*&gt; [1,2]</span> happens first, resulting in a list that's the same as <span class="fixed">[(1+),(2+),(1*),(2*)]</span>, because every function on the left gets applied to every value on the right. Then, <span class="fixed">[(1+),(2+),(1*),(2*)] &lt;*&gt; [3,4]</span> happens, which produces the final result.</p>
<p>Using the applicative style with lists is fun! Watch:</p>
<pre name="code" class="haskell:hs">
ghci&gt; (++) &lt;$&gt; ["ha","heh","hmm"] &lt;*&gt; ["?","!","."]
["ha?","ha!","ha.","heh?","heh!","heh.","hmm?","hmm!","hmm."]
</pre>
<p>Again, see how we used a normal function that takes two strings between two applicative functors of strings just by inserting the appropriate applicative operators.</p>
<p>You can view lists as non-deterministic computations. A value like <span class="fixed">100</span> or <span class="fixed">"what"</span> can be viewed as a deterministic computation that has only one result, whereas a list like <span class="fixed">[1,2,3]</span> can be viewed as a computation that can't decide on which result it wants to have, so it presents us with all of the possible results. So when you do something like <span class="fixed">(+) &lt;$&gt; [1,2,3] &lt;*&gt; [4,5,6]</span>, you can think of it as adding together two non-deterministic computations with <span class="fixed">+</span>, only to produce another non-deterministic computation that's even less sure about its result.</p>
<p>Using the applicative style on lists is often a good replacement for list comprehensions. In the second chapter, we wanted to see all the possible products of <span class="fixed">[2,5,10]</span> and <span class="fixed">[8,10,11]</span>, so we did this:</p>
<pre name="code" class="haskell:hs">
ghci&gt; [ x*y | x &lt;- [2,5,10], y &lt;- [8,10,11]]   
[16,20,22,40,50,55,80,100,110]   
</pre>
<p>We're just drawing from two lists and applying a function between every combination of elements. This can be done in the applicative style as well:</p>
<pre name="code" class="haskell:hs">
ghci&gt; (*) &lt;$&gt; [2,5,10] &lt;*&gt; [8,10,11]
[16,20,22,40,50,55,80,100,110]
</pre>
<p>This seems clearer to me, because it's easier to see that we're just calling <span class="fixed">*</span> between two non-deterministic computations. If we wanted all possible products of those two lists that are more than 50, we'd just do:</p>
<pre name="code" class="haskell:hs">
ghci&gt; filter (&gt;50) $ (*) &lt;$&gt; [2,5,10] &lt;*&gt; [8,10,11]
[55,80,100,110]
</pre>
<p>It's easy to see how <span class="fixed">pure f &lt;*&gt; xs</span> equals <span class="fixed">fmap f xs</span> with lists. <span class="fixed">pure f</span> is just <span class="fixed">[f]</span> and <span class="fixed">[f] &lt;*&gt; xs</span> will apply every function in the left list to every value in the right one, but there's just one function in the left list, so it's like mapping.</p>
<p>Another instance of <span class="fixed">Applicative</span> that we've already encountered is <span class="fixed">IO</span>. This is how the instance is implemented:</p>
<pre name="code" class="haskell:hs">
instance Applicative IO where
    pure = return
    a &lt;*&gt; b = do
        f &lt;- a
        x &lt;- b
        return (f x)
</pre>
<img src="knight.png" alt="ahahahah!" class="left" width="195" height="458">
<p>Since <span class="fixed">pure</span> is all about putting a value in a minimal context that still holds it as its result, it makes sense that <span class="fixed">pure</span> is just <span class="fixed">return</span>, because <span class="fixed">return</span> does exactly that; it makes an I/O action that doesn't do anything, it just yields some value as its result, but it doesn't really do any I/O operations like printing to the terminal or reading from a file.</p>
<p>If <span class="fixed">&lt;*&gt;</span> were specialized for <span class="fixed">IO</span> it would have a type of <span class="fixed">(&lt;*&gt;) :: IO (a -&gt; b) -&gt; IO a -&gt; IO b</span>. It would take an I/O action that yields a function as its result and another I/O action and create a new I/O action from those two that, when performed, first performs the first one to get the function and then performs the second one to get the value and then it would yield that function applied to the value as its result. We used <i>do</i> syntax to implement it here. Remember, <i>do</i> syntax is about taking several I/O actions and gluing them into one, which is exactly what we do here.</p>
<p>With <span class="fixed">Maybe</span> and <span class="fixed">[]</span>, we could think of <span class="fixed">&lt;*&gt;</span> as simply extracting a function from its left parameter and then sort of applying it over the right one. With <span class="fixed">IO</span>, extracting is still in the game, but now we also have a notion of <i>sequencing</i>, because we're taking two I/O actions and we're sequencing, or gluing, them into one. We have to extract the function from the first I/O action, but to extract a result from an I/O action, it has to be performed.</p>
<p>Consider this:</p>
<pre name="code" class="haskell:hs">
myAction :: IO String
myAction = do
    a &lt;- getLine
    b &lt;- getLine
    return $ a ++ b
</pre>
<p>This is an I/O action that will prompt the user for two lines and yield as its result those two lines concatenated. We achieved it by gluing together two <span class="fixed">getLine</span> I/O actions and a <span class="fixed">return</span>, because we wanted our new glued I/O action to hold the result of <span class="fixed">a ++ b</span>. Another way of writing this would be to use the applicative style.</p>
<pre name="code" class="haskell:hs">
myAction :: IO String
myAction = (++) &lt;$&gt; getLine &lt;*&gt; getLine
</pre>
<p>What we were doing before was making an I/O action that applied a function between the results of two other I/O actions, and this is the same thing. Remember, <span class="fixed">getLine</span> is an I/O action with the type <span class="fixed">getLine :: IO String</span>. When we use <span class="fixed">&lt;*&gt;</span> between two applicative functors, the result is an applicative functor, so this all makes sense.</p>
<p>If we regress to the box analogy, we can imagine <span class="fixed">getLine</span> as a box that will go out into the real world and fetch us a string. Doing <span class="fixed">(++) &lt;$&gt; getLine &lt;*&gt; getLine</span> makes a new, bigger box that sends those two boxes out to fetch lines from the terminal and then presents the concatenation of those two lines as its result.</p>
<p>The type of the expression <span class="fixed">(++) &lt;$&gt; getLine &lt;*&gt; getLine</span> is <span class="fixed">IO String</span>, which means that this expression is a completely normal I/O action like any other, which also holds a result value inside it, just like other I/O actions. That's why we can do stuff like:</p>
<pre name="code" class="haskell:hs">
main = do
    a &lt;- (++) &lt;$&gt; getLine &lt;*&gt; getLine
    putStrLn $ "The two lines concatenated turn out to be: " ++ a
</pre>
<p>If you ever find yourself binding some I/O actions to names and then calling some function on them and presenting that as the result by using <span class="fixed">return</span>, consider using the applicative style because it's arguably a bit more concise and terse.</p>
<p>Another instance of <span class="fixed">Applicative</span> is <span class="fixed">(-&gt;) r</span>, so functions. They are rarely used with the applicative style outside of code golf, but they're still interesting as applicatives, so let's take a look at how the function instance is implemented.</p>
<div class="hintbox">If you're confused about what <span class="fixed">(-&gt;) r</span> means, check out the previous section where we explain how <span class="fixed">(-&gt; r)</span> is a functor.</div>
<pre name="code" class="haskell:hs">
instance Applicative ((-&gt;) r) where
    pure x = (\_ -&gt; x)
    f &lt;*&gt; g = \x -&gt; f x (g x)
</pre>
<p>When we wrap a value into an applicative functor with <span class="fixed">pure</span>, the result it yields always has to be that value. A minimal default context that still yields that value as a result. That's why in the function instance implementation, <span class="fixed">pure</span> takes a value and creates a function that ignores its parameter and always returns that value. If we look at the type for <span class="fixed">pure</span>, but specialized for the <span class="fixed">(-&gt;) r</span> instance, it's <span class="fixed">pure :: a -&gt; (r -&gt; a)</span>.</p>
<pre name="code" class="haskell:hs">
ghci&gt; (pure 3) "blah"
3
</pre>
<p>Because of currying, function application is left-associative, so we can omit the parentheses.</p>
<pre name="code" class="haskell:hs">
ghci&gt; pure 3 "blah"
3
</pre>
<p>The instance implementation for <span class="fixed">&lt;*&gt;</span> is a bit cryptic, so it's best if we just take a look at how to use functions as applicative functors in the applicative style.</p>
<pre name="code" class="haskell:hs">
ghci&gt; :t (+) &lt;$&gt; (+3) &lt;*&gt; (*100)
(+) &lt;$&gt; (+3) &lt;*&gt; (*100) :: (Num a) =&gt; a -&gt; a
ghci&gt; (+) &lt;$&gt; (+3) &lt;*&gt; (*100) $ 5
508
</pre>
<p>Calling <span class="fixed">&lt;*&gt;</span> with two applicative functors results in an applicative functor, so if we use it on two functions, we get back a function. So what goes on here? When we do <span class="fixed">(+) &lt;$&gt; (+3) &lt;*&gt; (*100)</span>, we're making a function that will use <span class="fixed">+</span> on the results of <span class="fixed">(+3)</span> and <span class="fixed">(*100)</span> and return that. To demonstrate on a real example, when we did <span class="fixed">(+) &lt;$&gt; (+3) &lt;*&gt; (*100) $ 5</span>, the <span class="fixed">5</span> first got applied to <span class="fixed">(+3)</span> and <span class="fixed">(*100)</span>, resulting in <span class="fixed">8</span> and <span class="fixed">500</span>. Then, <span class="fixed">+</span> gets called with <span class="fixed">8</span> and <span class="fixed">500</span>, resulting in <span class="fixed">508</span>.</p>
<pre name="code" class="haskell:hs">
ghci&gt; (\x y z -&gt; [x,y,z]) &lt;$&gt; (+3) &lt;*&gt; (*2) &lt;*&gt; (/2) $ 5
[8.0,10.0,2.5]
</pre>
<img src="jazzb.png" alt="SLAP" class="right" width="400px" height="230px">
<p>Same here. We create a function that will call the function <span class="fixed">\x y z -&gt; [x,y,z]</span> with the eventual results from <span class="fixed">(+3)</span>, <span class="fixed">(*2)</span> and <span class="fixed">(/2)</span>. The <span class="fixed">5</span> gets fed to each of the three functions and then <span class="fixed">\x y z -&gt; [x, y, z]</span> gets called with those results.</p>
<p>You can think of functions as boxes that contain their eventual results, so doing <span class="fixed">k &lt;$&gt; f &lt;*&gt; g</span> creates a function that will call <span class="fixed">k</span> with the eventual results from <span class="fixed">f</span> and <span class="fixed">g</span>. When we do something like <span class="fixed">(+) &lt;$&gt; Just 3 &lt;*&gt; Just 5</span>, we're using <span class="fixed">+</span> on values that might or might not be there, which also results in a value that might or might not be there. When we do <span class="fixed">(+) &lt;$&gt; (+10) &lt;*&gt; (+5)</span>, we're using <span class="fixed">+</span> on the future return values of <span class="fixed">(+10)</span> and <span class="fixed">(+5)</span> and the result is also something that will produce a value only when called with a parameter.</p>
<p>We don't often use functions as applicatives, but this is still really interesting. It's not very important that you get how the <span class="fixed">(-&gt;) r</span> instance for <span class="fixed">Applicative</span> works, so don't despair if you're not getting this right now. Try playing with the applicative style and functions to build up an intuition for functions as applicatives.</p>
<p>An instance of <span class="fixed">Applicative</span> that we haven't encountered yet is <span class="fixed">ZipList</span>, and it lives in <span class="fixed">Control.Applicative</span>. </p>
<p>It turns out there are actually more ways for lists to be applicative functors. One way is the one we already covered, which says that calling <span class="fixed">&lt;*&gt;</span> with a list of functions and a list of values results in a list which has all the possible combinations of applying functions from the left list to the values in the right list. If we do <span class="fixed">[(+3),(*2)] &lt;*&gt; [1,2]</span>, <span class="fixed">(+3)</span> will be applied to both <span class="fixed">1</span> and <span class="fixed">2</span> and <span class="fixed">(*2)</span> will also be applied to both <span class="fixed">1</span> and <span class="fixed">2</span>, resulting in a list that has four elements, namely <span class="fixed">[4,5,2,4]</span>.</p>
<p>However, <span class="fixed">[(+3),(*2)] &lt;*&gt; [1,2]</span> could also work in such a way that the first function in the left list gets applied to the first value in the right one, the second function gets applied to the second value, and so on. That would result in a list with two values, namely <span class="fixed">[4,4]</span>. You could look at it as <span class="fixed">[1 + 3, 2 * 2]</span>.</p>
<p>Because one type can't have two instances for the same typeclass, the <span class="fixed">ZipList a</span> type was introduced, which has one constructor <span class="fixed">ZipList</span> that has just one field, and that field is a list. Here's the instance:</p>
<pre name="code" class="haskell:hs">
instance Applicative ZipList where
        pure x = ZipList (repeat x)
        ZipList fs &lt;*&gt; ZipList xs = ZipList (zipWith (\f x -&gt; f x) fs xs)
</pre>
<p><span class="fixed">&lt;*&gt;</span> does just what we said. It applies the first function to the first value, the second function to the second value, etc. This is done with <span class="fixed">zipWith (\f x -&gt; f x) fs xs</span>. Because of how <span class="fixed">zipWith</span> works, the resulting list will be as long as the shorter of the two lists.</p>
<p><span class="fixed">pure</span> is also interesting here. It takes a value and puts it in a list that just has that value repeating indefinitely. <span class="fixed">pure "haha"</span> results in <span class="fixed">ZipList (["haha","haha","haha"...</span>. This might be a bit confusing since we said that <span class="fixed">pure</span> should put a value in a minimal context that still yields that value. And you might be thinking that an infinite list of something is hardly minimal. But it makes sense with zip lists, because it has to produce the value on every position. This also satisfies the law that <span class="fixed">pure f &lt;*&gt; xs</span> should equal <span class="fixed">fmap f xs</span>. If <span class="fixed">pure 3</span> just returned <span class="fixed">ZipList [3]</span>, <span class="fixed">pure (*2) &lt;*&gt; ZipList [1,5,10]</span> would result in <span class="fixed">ZipList [2]</span>, because the resulting list of two zipped lists has the length of the shorter of the two. If we zip a finite list with an infinite list, the length of the resulting list will always be equal to the length of the finite list.</p>
<p>So how do zip lists work in an applicative style? Let's see. Oh, the <span class="fixed">ZipList a</span> type doesn't have a <span class="fixed">Show</span> instance, so we have to use the <span class="label function">getZipList</span> function to extract a raw list out of a zip list.</p>
<pre name="code" class="haskell:hs">
ghci&gt; getZipList $ (+) &lt;$&gt; ZipList [1,2,3] &lt;*&gt; ZipList [100,100,100]
[101,102,103]
ghci&gt; getZipList $ (+) &lt;$&gt; ZipList [1,2,3] &lt;*&gt; ZipList [100,100..]
[101,102,103]
ghci&gt; getZipList $ max &lt;$&gt; ZipList [1,2,3,4,5,3] &lt;*&gt; ZipList [5,3,1,2]
[5,3,3,4]
ghci&gt; getZipList $ (,,) &lt;$&gt; ZipList "dog" &lt;*&gt; ZipList "cat" &lt;*&gt; ZipList "rat"
[('d','c','r'),('o','a','a'),('g','t','t')]
</pre>
<div class="hintbox">The <span class="fixed">(,,)</span> function is the same as <span class="fixed">\x y z -&gt; (x,y,z)</span>. Also, the <span class="fixed">(,)</span> function is the same as <span class="fixed">\x y -&gt; (x,y)</span>.</div>
<p>Aside from <span class="fixed">zipWith</span>, the standard library has functions such as <span class="fixed">zipWith3</span>, <span class="fixed">zipWith4</span>, all the way up to 7. <span class="fixed">zipWith</span> takes a function that takes two parameters and zips two lists with it. <span class="fixed">zipWith3</span> takes a function that takes three parameters and zips three lists with it, and so on. By using zip lists with an applicative style, we don't have to have a separate zip function for each number of lists that we want to zip together. We just use the applicative style to zip together an arbitrary amount of lists with a function, and that's pretty cool.</p>
<p><span class="fixed">Control.Applicative</span> defines a function that's called <span class="label function">liftA2</span>, which has a type of <span class="fixed">liftA2 :: (Applicative f) =&gt; (a -&gt; b -&gt; c) -&gt; f a -&gt; f b -&gt; f c</span> . It's defined like this:</p>
<pre name="code" class="haskell:hs">
liftA2 :: (Applicative f) =&gt; (a -&gt; b -&gt; c) -&gt; f a -&gt; f b -&gt; f c
liftA2 f a b = f &lt;$&gt; a &lt;*&gt; b
</pre>
<p>Nothing special, it just applies a function between two applicatives, hiding the applicative style that we've become familiar with. The reason we're looking at it is because it clearly showcases why applicative functors are more powerful than just ordinary functors. With ordinary functors, we can just map functions over one functor. But with applicative functors, we can apply a function between several functors. It's also interesting to look at this function's type as <span class="fixed">(a -&gt; b -&gt; c) -&gt; (f a -&gt; f b -&gt; f c)</span>. When we look at it like this, we can say that <span class="fixed">liftA2</span> takes a normal binary function and promotes it to a function that operates on two functors.</p>
<p>Here's an interesting concept: we can take two applicative functors and combine them into one applicative functor that has inside it the results of those two applicative functors in a list. For instance, we have <span class="fixed">Just 3</span> and <span class="fixed">Just 4</span>. Let's assume that the second one has a singleton list inside it, because that's really easy to achieve:</p>
<pre name="code" class="haskell:hs">
ghci&gt; fmap (\x -&gt; [x]) (Just 4)
Just [4]
</pre>
<p>OK, so let's say we have <span class="fixed">Just 3</span> and <span class="fixed">Just [4]</span>. How do we get <span class="fixed">Just [3,4]</span>? Easy.</p>
<pre name="code" class="haskell:hs">
ghci&gt; liftA2 (:) (Just 3) (Just [4])
Just [3,4]
ghci&gt; (:) &lt;$&gt; Just 3 &lt;*&gt; Just [4]
Just [3,4]
</pre>
<p>Remember, <span class="fixed">:</span> is a function that takes an element and a list and returns a new list with that element at the beginning. Now that we have <span class="fixed">Just [3,4]</span>, could we combine that with <span class="fixed">Just 2</span> to produce <span class="fixed">Just [2,3,4]</span>? Of course we could. It seems that we can combine any amount of applicatives into one applicative that has a list of the results of those applicatives inside it. Let's try implementing a function that takes a list of applicatives and returns an applicative that has a list as its result value. We'll call it <span class="fixed">sequenceA</span>.</p>
<pre name="code" class="haskell:hs">
sequenceA :: (Applicative f) =&gt; [f a] -&gt; f [a]
sequenceA [] = pure []
sequenceA (x:xs) = (:) &lt;$&gt; x &lt;*&gt; sequenceA xs
</pre>
<p>Ah, recursion! First, we look at the type. It will transform a list of applicatives into an applicative with a list. From that, we can lay some groundwork for an edge condition. If we want to turn an empty list into an applicative with a list of results, well, we just put an empty list in a default context. Now comes the recursion. If we have a list with a head and a tail (remember, <span class="fixed">x</span> is an applicative and <span class="fixed">xs</span> is a list of them), we call <span class="fixed">sequenceA</span> on the tail, which results in an applicative with a list. Then, we just prepend the value inside the applicative <span class="fixed">x</span> into that applicative with a list, and that's it!</p>
<p>So if we do <span class="fixed">sequenceA [Just 1, Just 2]</span>, that's <span class="fixed">(:) &lt;$&gt; Just 1 &lt;*&gt; sequenceA [Just 2] </span>. That equals <span class="fixed">(:) &lt;$&gt; Just 1 &lt;*&gt; ((:) &lt;$&gt; Just 2 &lt;*&gt; sequenceA [])</span>. Ah! We know that <span class="fixed">sequenceA []</span> ends up as being <span class="fixed">Just []</span>, so this expression is now <span class="fixed">(:) &lt;$&gt; Just 1 &lt;*&gt; ((:) &lt;$&gt; Just 2 &lt;*&gt; Just [])</span>, which is <span class="fixed">(:) &lt;$&gt; Just 1 &lt;*&gt; Just [2]</span>, which is <span class="fixed">Just [1,2]</span>!</p>
<p>Another way to implement <span class="fixed">sequenceA</span> is with a fold. Remember, pretty much any function where we go over a list element by element and accumulate a result along the way can be implemented with a fold.</p>
<pre name="code" class="haskell:hs">
sequenceA :: (Applicative f) =&gt; [f a] -&gt; f [a]
sequenceA = foldr (liftA2 (:)) (pure [])
</pre>
<p>We approach the list from the right and start off with an accumulator value of <span class="fixed">pure []</span>. We do <span class="fixed">liftA2 (:)</span> between the accumulator and the last element of the list, which results in an applicative that has a singleton in it. Then we do <span class="fixed">liftA2 (:)</span> with the now last element and the current accumulator and so on, until we're left with just the accumulator, which holds a list of the results of all the applicatives.</p>
<p>Let's give our function a whirl on some applicatives.</p>
<pre name="code" class="haskell:hs">
ghci&gt; sequenceA [Just 3, Just 2, Just 1]
Just [3,2,1]
ghci&gt; sequenceA [Just 3, Nothing, Just 1]
Nothing
ghci&gt; sequenceA [(+3),(+2),(+1)] 3
[6,5,4]
ghci&gt; sequenceA [[1,2,3],[4,5,6]]
[[1,4],[1,5],[1,6],[2,4],[2,5],[2,6],[3,4],[3,5],[3,6]]
ghci&gt; sequenceA [[1,2,3],[4,5,6],[3,4,4],[]]
[]
</pre>
<p>Ah! Pretty cool. When used on <span class="fixed">Maybe</span> values, <span class="fixed">sequenceA</span> creates a <span class="fixed">Maybe</span> value with all the results inside it as a list. If one of the values was <span class="fixed">Nothing</span>, then the result is also a <span class="fixed">Nothing</span>. This is cool when you have a list of <span class="fixed">Maybe</span> values and you're interested in the values only if none of them is a <span class="fixed">Nothing</span>.</p>
<p>When used with functions, <span class="fixed">sequenceA</span> takes a list of functions and returns a function that returns a list. In our example, we made a function that took a number as a parameter and applied it to each function in the list and then returned a list of results. <span class="fixed">sequenceA [(+3),(+2),(+1)] 3</span> will call <span class="fixed">(+3)</span> with <span class="fixed">3</span>, <span class="fixed">(+2)</span> with <span class="fixed">3</span> and <span class="fixed">(+1)</span> with <span class="fixed">3</span> and present all those results as a list.</p>
<p>Doing <span class="fixed">(+) &lt;$&gt; (+3) &lt;*&gt; (*2)</span> will create a function that takes a parameter, feeds it to both <span class="fixed">(+3)</span> and <span class="fixed">(*2)</span> and then calls <span class="fixed">+</span> with those two results. In the same vein, it makes sense that <span class="fixed">sequenceA [(+3),(*2)]</span> makes a function that takes a parameter and feeds it to all of the functions in the list. Instead of calling <span class="fixed">+</span> with the results of the functions, a combination of <span class="fixed">:</span> and <span class="fixed">pure []</span> is used to gather those results in a list, which is the result of that function.</p>
<p>Using <span class="fixed">sequenceA</span> is cool when we have a list of functions and we want to feed the same input to all of them and then view the list of results. For instance, we have a number and we're wondering whether it satisfies all of the predicates in a list. One way to do that would be like so:</p>
<pre name="code" class="haskell:hs">
ghci&gt; map (\f -&gt; f 7) [(&gt;4),(&lt;10),odd]
[True,True,True]
ghci&gt; and $ map (\f -&gt; f 7) [(&gt;4),(&lt;10),odd]
True
</pre>
<p>Remember, <span class="fixed">and</span> takes a list of booleans and returns <span class="fixed">True</span> if they're all <span class="fixed">True</span>. Another way to achieve the same thing would be with <span class="fixed">sequenceA</span>:</p>
<pre name="code" class="haskell:hs">
ghci&gt; sequenceA [(&gt;4),(&lt;10),odd] 7
[True,True,True]
ghci&gt; and $ sequenceA [(&gt;4),(&lt;10),odd] 7
True
</pre>
<p><span class="fixed">sequenceA [(&gt;4),(&lt;10),odd]</span> creates a function that will take a number and feed it to all of the predicates in <span class="fixed">[(&gt;4),(&lt;10),odd]</span> and return a list of booleans. It turns a list with the type <span class="fixed">(Num a) =&gt; [a -&gt; Bool]</span> into a function with the type <span class="fixed">(Num a) =&gt; a -&gt; [Bool]</span>. Pretty neat, huh?</p>
<p>Because lists are homogenous, all the functions in the list have to be functions of the same type, of course. You can't have a list like <span class="fixed">[ord, (+3)]</span>, because <span class="fixed">ord</span> takes a character and returns a number, whereas <span class="fixed">(+3)</span> takes a number and returns a number.</p>
<p>When used with <span class="fixed">[]</span>, <span class="fixed">sequenceA</span> takes a list of lists and returns a list of lists. Hmm, interesting. It actually creates lists that have all possible combinations of their elements. For illustration, here's the above done with <span class="fixed">sequenceA</span> and then done with a list comprehension:</p>
<pre name="code" class="haskell:hs">
ghci&gt; sequenceA [[1,2,3],[4,5,6]]
[[1,4],[1,5],[1,6],[2,4],[2,5],[2,6],[3,4],[3,5],[3,6]]
ghci&gt; [[x,y] | x &lt;- [1,2,3], y &lt;- [4,5,6]]
[[1,4],[1,5],[1,6],[2,4],[2,5],[2,6],[3,4],[3,5],[3,6]]
ghci&gt; sequenceA [[1,2],[3,4]]
[[1,3],[1,4],[2,3],[2,4]]
ghci&gt; [[x,y] | x &lt;- [1,2], y &lt;- [3,4]]
[[1,3],[1,4],[2,3],[2,4]]
ghci&gt; sequenceA [[1,2],[3,4],[5,6]]
[[1,3,5],[1,3,6],[1,4,5],[1,4,6],[2,3,5],[2,3,6],[2,4,5],[2,4,6]]
ghci&gt; [[x,y,z] | x &lt;- [1,2], y &lt;- [3,4], z &lt;- [5,6]]
[[1,3,5],[1,3,6],[1,4,5],[1,4,6],[2,3,5],[2,3,6],[2,4,5],[2,4,6]]
</pre>
<p>This might be a bit hard to grasp, but if you play with it for a while, you'll see how it works. Let's say that we're doing <span class="fixed">sequenceA [[1,2],[3,4]]</span>. To see how this happens, let's use the <span class="fixed">sequenceA (x:xs) = (:) &lt;$&gt; x &lt;*&gt; sequenceA xs</span> definition of <span class="fixed">sequenceA</span> and the edge condition <span class="fixed">sequenceA [] = pure []</span>. You don't have to follow this evaluation, but it might help you if have trouble imagining how <span class="fixed">sequenceA</span> works on lists of lists, because it can be a bit mind-bending. </p>
<ul>
    <li>We start off with <span class="fixed">sequenceA [[1,2],[3,4]]</span></li>
    <li>That evaluates to <span class="fixed">(:) &lt;$&gt; [1,2] &lt;*&gt; sequenceA [[3,4]]</span></li>
    <li>Evaluating the inner <span class="fixed">sequenceA</span> further, we get <span class="fixed">(:) &lt;$&gt; [1,2] &lt;*&gt; ((:) &lt;$&gt; [3,4] &lt;*&gt; sequenceA [])</span></li>
    <li>We've reached the edge condition, so this is now <span class="fixed">(:) &lt;$&gt; [1,2] &lt;*&gt; ((:) &lt;$&gt; [3,4] &lt;*&gt; [[]])</span></li>
    <li>Now, we evaluate the <span class="fixed">(:) &lt;$&gt; [3,4] &lt;*&gt; [[]]</span> part, which will use <span class="fixed">:</span> with every possible value in the left list (possible values are <span class="fixed">3</span> and <span class="fixed">4</span>) with every possible value on the right list (only possible value is <span class="fixed">[]</span>), which results in <span class="fixed">[3:[], 4:[]]</span>, which is <span class="fixed">[[3],[4]]</span>. So now we have <span class="fixed">(:) &lt;$&gt; [1,2] &lt;*&gt; [[3],[4]]</span></li>
    <li>Now, <span class="fixed">:</span> is used with every possible value from the left list (<span class="fixed">1</span> and <span class="fixed">2</span>) with every possible value in the right list (<span class="fixed">[3]</span> and <span class="fixed">[4]</span>), which results in <span class="fixed">[1:[3], 1:[4], 2:[3], 2:[4]]</span>, which is <span class="fixed">[[1,3],[1,4],[2,3],[2,4]</span></li>
</ul>
<p>When used with I/O actions, <span class="fixed">sequenceA</span> is the same thing as <span class="fixed">sequence</span>! It takes a list of I/O actions and returns an I/O action that will perform each of those actions and have as its result a list of the results of those I/O actions. That's because to turn an <span class="fixed">[IO a]</span> value into an <span class="fixed">IO [a]</span> value, to make an I/O action that yields a list of results when performed, all those I/O actions have to be sequenced so that they're then performed one after the other when evaluation is forced. You can't get the result of an I/O action without performing it.</p>
<pre name="code" class="haskell:hs">
ghci&gt; sequenceA [getLine, getLine, getLine]
heyh
ho
woo
["heyh","ho","woo"]
</pre>
<p>Like normal functors, applicative functors come with a few laws. The most important one is the one that we already mentioned, namely that <span class="label law">pure f &lt;*&gt; x = fmap f x</span> holds. As an exercise, you can prove this law for some of the applicative functors that we've met in this chapter.The other functor laws are:</p>
<ul>
    <li><span class="label law">pure id &lt;*&gt; v = v</span></li>
    <li><span class="label law">pure (.) &lt;*&gt; u &lt;*&gt; v &lt;*&gt; w = u &lt;*&gt; (v &lt;*&gt; w)</span></li>
    <li><span class="label law">pure f &lt;*&gt; pure x = pure (f x)</span></li>
    <li><span class="label law">u &lt;*&gt; pure y = pure ($ y) &lt;*&gt; u</span></li>
</ul>
<p>We won't go over them in detail right now because that would take up a lot of pages and it would probably be kind of boring, but if you're up to the task, you can take a closer look at them and see if they hold for some of the instances.</p>
<p>In conclusion, applicative functors aren't just interesting, they're also useful, because they allow us to combine different computations, such as I/O computations, non-deterministic computations, computations that might have failed, etc. by using the applicative style. Just by using <span class="fixed">&lt;$&gt;</span> and <span class="fixed">&lt;*&gt;</span> we can use normal functions to uniformly operate on any number of applicative functors and take advantage of the semantics of each one.</p>
<a name="newtype"></a><h2><?=$contents[$_P[0]]['subchapters']['newtype']?></h2>
